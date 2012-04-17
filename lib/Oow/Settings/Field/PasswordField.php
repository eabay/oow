<?php

namespace Oow\Settings\Field;

class PasswordField extends TextField
{
    protected $forcedAttribs = array(
        'type' => 'password'
    );
}