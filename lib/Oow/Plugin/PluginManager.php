<?php

namespace Oow\Plugin;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;

class PluginManager
{
    protected $annotationReader;

    public function __construct(Reader $reader = null)
    {
        AnnotationRegistry::registerFile(__DIR__ . '/Annotations/PluginAnnotations.php');

        if ($reader) {
            $this->setAnnotationReader($reader);
        }
    }

    public function setAnnotationReader(Reader $reader)
    {
        $this->annotationReader = $reader;

        return $this;
    }

    /**
     * @return Reader
     */
    public function getAnnotationReader()
    {
        if (!$this->annotationReader) {
            $reader = new SimpleAnnotationReader;
            $reader->addNamespace('Oow\Plugin\Annotations');

            $this->annotationReader = $reader;
        }

        return $this->annotationReader;
    }

    public function addPlugin($plugin)
    {
        $reflClass = new \ReflectionClass($plugin);

        if (!$this->getAnnotationReader()->getClassAnnotation($reflClass, 'Oow\Plugin\Annotations\Plugin')) {
            throw new \InvalidArgumentException("{$reflClass->getName()} does not have any Oow\\Plugin\\Annotations\\Plugin annotation instance");
        }

        foreach ($reflClass->getMethods() as $method) {
            if ($method->isPublic()) {
                foreach ($this->getAnnotationReader()->getMethodAnnotations($method) as $annot) {
                    if ($annot instanceof Annotations\Hook) {
                        $tag             = $annot->tag;
                        $function_to_add = array($plugin, $method->getName());
                        $priority        = $annot->priority;
                        $accepted_args   = $method->getNumberOfParameters();

                        add_filter($tag, $function_to_add, $priority, $accepted_args);
                    } elseif ($annot instanceof Annotations\Settings) {
                        $this->addPlugin($plugin->{$method->getName()}());
                    } elseif ($annot instanceof Annotations\Shortcode) {
                        $tag  = $annot->tag;
                        $func = array($plugin, $method->getName());

                        add_shortcode($tag, $func);
                    } elseif ($annot instanceof Annotations\AjaxResponse) {
                        $closure = function() use ($plugin, $method, $annot) {
                            if (isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($_REQUEST['_wpnonce'], $annot->action)) {
                                $response = false;
                            } else {
                                $response = $plugin->{$method->getName()}();
                            }

                            if ($annot->json) {
                                wp_send_json($response);
                            } else {
                                echo $response;
                                wp_die();
                            }
                        };

                        add_action('wp_ajax_'. $annot->action, $closure);

                        if ($annot->nopriv) {
                            add_action('wp_ajax_nopriv_'. $annot->action, $closure);
                        }
                    } elseif ($annot instanceof Annotations\Embed) {
                        $id       = $annot->id;
                        $regex    = $annot->regex;
                        $func     = array($plugin, $method->getName());
                        $priority = $annot->priority;

                        wp_embed_register_handler($id, $regex, $func, $priority);
                    }
                }
            }
        }

        return $this;
    }

    public function addPlugins(array $plugins)
    {
        foreach ($plugins as $plugin) {
            $this->addPlugin($plugin);
        }
    }

    public function addWidget($widget)
    {
        $reflClass = new \ReflectionClass($widget);

        if (!$this->getAnnotationReader()->getClassAnnotation($reflClass, 'Oow\Plugin\Annotations\Widget')) {
            throw new \InvalidArgumentException("{$reflClass->getName()} does not have any Oow\\Plugin\\Annotations\\Widget annotation instance");
        }

        add_action('widgets_init', function () use ($widget) {
            global $wp_widget_factory;

            $wp_widget_factory->widgets[get_class($widget)] = $widget;
        });

        $this->addPlugin($widget);

        return $this;
    }

    public function addWidgets(array $widgets)
    {
        foreach ($widgets as $widget) {
            $this->addWidget($widget);
        }
    }
}
