<?php

namespace Francis\Bundle\ReCaptchaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ReCaptcha extends Constraint
{
    public $secretMessage = 'An internal error prevented to check that you are not a robot.';
    public $invalidMessage = 'You should specify that you are not a robot.';
}
