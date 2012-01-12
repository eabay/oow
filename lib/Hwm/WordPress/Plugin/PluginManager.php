<?php

namespace Hwm\WordPress\Plugin;

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
    
    public function getAnnotationReader()
    {
        if (!$this->annotationReader) {
            $reader = new SimpleAnnotationReader;
            $reader->addNamespace('Hwm\WordPress\Plugin\Annotations');
            
            $this->annotationReader = $reader;
        }
        
        return $this->annotationReader;
    }
    
    public function addPlugin($plugin)
    {
        $reflClass = new \ReflectionClass($plugin);
        
        if (!$this->getAnnotationReader()->getClassAnnotation($reflClass, 'Hwm\WordPress\Plugin\Annotations\Plugin')) {
            throw new \InvalidArgumentException("{$reflClass->getName()} does not have any Hwm\WordPress\Plugin\Annotations\Plugin annotation instance");
        }
        
        foreach ($reflClass->getMethods() as $method) {
            if ($method->isPublic()) {
                foreach ($this->getAnnotationReader()->getMethodAnnotations($method) as $annot) {
                    if ($annot instanceof \Hwm\WordPress\Plugin\Annotations\Hook) {
                        $tag             = $annot->tag;
                        $function_to_add = array($plugin, $method->getName());
                        $priority        = $annot->priority;
                        $accepted_args   = $method->getNumberOfParameters();
                        
                        add_filter($tag, $function_to_add, $priority, $accepted_args);
                    } elseif ($annot instanceof \Hwm\WordPress\Plugin\Annotations\Settings) {
                        $this->addPlugin($plugin->{$method->getName()}());
                    } elseif ($annot instanceof \Hwm\WordPress\Plugin\Annotations\Shortcode) {
                        $tag  = $annot->tag;
                        $func = array($plugin, $method->getName());
                        
                        add_shortcode($tag, $func);
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
        
        if (!$this->getAnnotationReader()->getClassAnnotation($reflClass, 'Hwm\WordPress\Plugin\Annotations\Widget')) {
            throw new \InvalidArgumentException("{$reflClass->getName()} does not have any Hwm\WordPress\Plugin\Annotations\Widget annotation instance");
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