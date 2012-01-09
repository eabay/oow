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
}