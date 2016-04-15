<?php

namespace Francis\Bundle\ReCaptchaBundle\ReCaptcha;

use ReCaptcha\Response as GoogleResponse;

class CurlResponse extends Response
{
    private $response;

    private $info;

    public function __construct(GoogleResponse $response, array $info = null)
    {
        parent::__construct(null);

        $this->response = $response;
        $this->info = $info;
    }

    public function isSuccess()
    {
        return $this->response->isSuccess();
    }

    public function getErrorCodes()
    {
        return $this->response->getErrorCodes();
    }

    public function getInfo()
    {
        return $this->info;
    }
}
