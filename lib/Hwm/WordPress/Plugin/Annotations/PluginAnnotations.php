<?php

namespace Hwm\WordPress\Plugin\Annotations;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
final class Plugin extends Annotation {}

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