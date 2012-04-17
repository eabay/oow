<?php

namespace Oow\Settings;

class Section
{
    /**
     * @var string
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var \Closure
     */
    protected $callback;
    
    /**
     * @param string $id
     * @param string $title
     * @param string|\Closure $callback
     */
    public function __construct($id, $title, $callback = '')
    {
        $this->id = $id;
        $this->title = $title;
        
        if (!is_callable($callback)) {
            $callback = function () use ($callback) {
                echo $callback;
            };
        }

        $this->callback = $callback;
    }
    
    /**
     * @return string $id;
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string $title;
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @return \Closure $callback;
     */
    public function getCallback()
    {
        return $this->callback;
    }
}