<?php

namespace Francis\Bundle\ReCaptchaBundle\ReCaptcha;

use ReCaptcha\Response as BaseResponse;

class Response extends BaseResponse
{
    const MISSING_SECRET = 'missing-input-secret';

    const INVALID_SECRET = 'invalid-input-secret';

    const MISSING_RESPONSE = 'missing-input-response';

    const INVALID_RESPONSE = 'invalid-input-response';

    public function hasSecretError()
    {
        foreach ($this->getErrorCodes() as $code) {
            if (static::MISSING_SECRET === $code || static::INVALID_SECRET === $code) {
                return true;
            }
        }
    }
}
