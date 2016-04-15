<?php

namespace Francis\Bundle\ReCaptchaBundle\ReCaptcha;

use Francis\Bundle\ReCaptchaBundle\ReCaptcha\RequestMethod\Curl;
use ReCaptcha\ReCaptcha as BaseReCaptcha;
use ReCaptcha\RequestMethod\CurlPost;

class ReCaptcha extends BaseReCaptcha
{
    public function __construct($secretKey, Curl $curl = null)
    {
        $this->curl = $curl ?: new Curl();

        parent::__construct($secretKey, new CurlPost($this->curl));
    }

    public function verify($response, $remoteIp = null)
    {
        $response = new CurlResponse(
            parent::verify($response, $remoteIp),
            $this->curl->getLastInfo()
        );

        return $response;
    }
}
