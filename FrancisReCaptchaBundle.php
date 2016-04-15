<?php

namespace Francis\Bundle\ReCaptchaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrancisReCaptchaBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $class = $this->getContainerExtensionClass();
            $this->extension = new $class;
        }

        return $this->extension;
    }
}
