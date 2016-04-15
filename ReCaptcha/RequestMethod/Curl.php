<?php

namespace Francis\Bundle\ReCaptchaBundle\ReCaptcha\RequestMethod;

use ReCaptcha\RequestMethod\Curl as BaseCurl;

class Curl extends BaseCurl
{
    private $lastInfo;

    public function exec($ch)
    {
        $response = parent::exec($ch);
        $this->lastInfo = curl_getinfo($ch);

        return $response;
    }

    public function getLastInfo()
    {
        return $this->lastInfo;
    }
}
