# What is Oow?

Oow is a library that contains components to ease [WordPress] plugin development.


## Design

If you are familiar with how Doctrine2 works, there is just a little to explain. There is a `PluginManager` to handle your standalone plugin classes by reading annotations. Don't worry if this doesn't make any sense to you. Continue to read. :)


### PluginManager

`PluginManager` is at the heart of the library and all classes is registered to WordPress hooks with it.


### Plugins

Plugins are just standalone PHP classes. They don't have to extend any base class and don't have to follow any naming conventions. Annotations in docblocks are used to handle classes.


## Requirements

* PHP 5.3.2 and up
* [Doctrine Annotations] library to process annotations


## Installation

### with [Composer]

Add `eabay/oow` as a dependency in your `composer.json` file.


## Usage

First create a plugin class:

``` php
<?php
namespace Plugins;

/** @Plugin */
class HelloWorld
{
    /** @Hook(tag="wp_footer",priority=50) */
    public function sayHello()
    {
        echo 'Hello World';
    }
}
```

Go to your plugin file and register your plugin with `PluginManager`;

``` php
<?php
/*
Plugin Name: Hello World
*/

use Oow\Plugin\PluginManager;
use Plugins\HelloWorld;

$manager = new PluginManager;

$manager->addPlugin(new HelloWorld);
```

This will print *Hello World* in the page footer. Check https://github.com/eabay/oow-sample-plugin for sample plugin.

You are free to create your classes as you want. `@Plugin` class annotation makes your class a plugin and `@Hook` method annotation hooks your its methods to actions and filters. If you semantically distinguish you action and filter hooks you can use `@Action` and `@Filter` annotations in method docblocks. Those are just synonyms and do exactly the same.

The parameters of `@Hook` is the same as [add_filter] function except that *function_to_add* is the method you assign and *accepted_args* is the number of arguments that method has. *priority* is optional and its default is *10*.

Please check [Doctrine Annotations] documentation to learn more about annotations.


## Contributing

Fork the project, create a feature branch, and send me a pull request.


[WordPress]: http://wordpress.org/
[composer]: http://getcomposer.org/
[Doctrine Annotations]: http://docs.doctrine-project.org/projects/doctrine-common/en/latest/reference/annotations.html
[add_filter]: http://codex.wordpress.org/Function_Reference/add_filter