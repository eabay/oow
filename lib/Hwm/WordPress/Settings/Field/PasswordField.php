<?php

namespace Hwm\WordPress\Settings\Field;

class PasswordField extends TextField
{
    protected $forcedAttribs = array(
        'type' => 'password'
    );
}