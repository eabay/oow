<?php

namespace Oow\Plugin\Annotations;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
class Plugin extends Annotation {}

/** @Annotation */
final class Widget extends Plugin {}

/** @Annotation */
class Hook extends Annotation
{
    public $tag;
    public $priority = 10;
}

/** @Annotation */
final class Action extends Hook {}

/** @Annotation */
final class Filter extends Hook {}

/** @Annotation */
final class Settings extends Annotation {}

/** @Annotation */
final class Shortcode extends Annotation
{
    public $tag;
}

/**
 * @Annotation
 * @Target("METHOD")
 */
final class AjaxResponse
{
    /** @var string */
    public $action;
    /** @var boolean */
    public $nopriv = false;
    /** @var boolean */
    public $json = true;
}

/** @Annotation */
final class Embed extends Annotation {
    public $id;
    public $regex;
    public $priority = 10;
}
