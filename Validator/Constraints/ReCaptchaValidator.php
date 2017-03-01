<?php

namespace Francis\Bundle\ReCaptchaBundle\Validator\Constraints;

use Francis\Bundle\ReCaptchaBundle\ReCaptcha\CurlResponse;
use Francis\Bundle\ReCaptchaBundle\ReCaptcha\ReCaptcha as ReCaptchaVerifier;
use Francis\Bundle\ReCaptchaBundle\ReCaptcha\Response;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReCaptcha\Response as ReCaptchaResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ReCaptchaValidator extends ConstraintValidator
{
    private $reCaptcha;

    private $requestStack;

    private $logger;

    public function __construct(ReCaptchaVerifier $reCaptcha, RequestStack $requestStack = null, LoggerInterface $logger = null)
    {
        $this->reCaptcha = $reCaptcha;
        $this->requestStack = $requestStack;
        $this->logger = $logger ?: new NullLogger();
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ReCaptcha) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ReCaptcha');
        }

        $remoteIp = null;
        if ($this->requestStack) {
            $remoteIp = $this->requestStack->getMasterRequest()->getClientIp();
        }

        $response = $this->reCaptcha->verify($value, $remoteIp);
        $this->log($response);

        if (!$response->isSuccess()) {
            $violationMessage = $response->hasInternalError() ? $constraint->internalMessage : $constraint->invalidMessage;
            $this->context->buildViolation($violationMessage)->addViolation();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'francis_recaptcha.validator.recaptcha';
    }

    protected function log(ReCaptchaResponse $response)
    {
        $context = array();
        $msg = 'ReCaptcha success';
        $logLevel = 'info';

        if ($response instanceof CurlResponse) {
            $context['curl'] = $response->getInfo();
        }

        if (!$response->isSuccess()) {
            $msg = 'ReCaptcha fail';
            $context['error_code'] = $response->getErrorCodes();

            if ($response instanceof Response && $response->hasInternalError()) {
                $msg = 'ReCaptcha error';
                $logLevel = 'error';
            }
        }

        call_user_func(array($this->logger, $logLevel), $msg, $context);
    }
}
