<?php

namespace Francis\Bundle\ReCaptchaBundle\Form\Type;

use Francis\Bundle\ReCaptchaBundle\Validator\Constraints\ReCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReCaptchaType extends AbstractType
{
    const VALIDATION_GROUP = 'recaptcha';

    private $requestStack;

    private $siteKey;

    public function __construct(RequestStack $requestStack, $siteKey)
    {
        $this->requestStack = $requestStack;
        $this->siteKey = $siteKey;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['sitekey'] = $this->siteKey;
        $view->vars['locale'] = $this->requestStack->getCurrentRequest()->getLocale();

        $view->vars['theme'] = isset($options['theme']) ? $options['theme'] : null;
        $view->vars['type'] = isset($options['type']) ? $options['type'] : null;
        $view->vars['size'] = isset($options['size']) ? $options['size'] : null;
        $view->vars['tabindex'] = isset($options['tabindex']) ? $options['tabindex'] : null;
        $view->vars['callback'] = isset($options['callback']) ? $options['callback'] : null;
        $view->vars['expired_callback'] = isset($options['expired_callback']) ? $options['expired_callback'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $request = $this->requestStack->getCurrentRequest();
            switch ($event->getForm()->getRoot()->getConfig()->getMethod()) {
                case 'GET':
                case 'HEAD':
                case 'TRACE':
                    $bag = $request->query;
                    break;

                default:
                    $bag = $request->request;
            }

            if (true === $bag->has('g-recaptcha-response')) {
                $event->setData($bag->get('g-recaptcha-response'));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => false,
            'constraints' => new ReCaptcha([
                'groups' => self::VALIDATION_GROUP,
            ]),
        ))->setDefined(array(
            'theme',
            'type',
            'size',
            'callback',
            'expired_callback',
        ));

        $resolver
            ->setAllowedValues('theme', array('dark', 'light'))
            ->setAllowedValues('type', array('audio', 'image'))
            ->setAllowedValues('size', array('compact', 'normal'))
            ->setAllowedTypes('callback', 'string')
            ->setAllowedTypes('expired_callback', 'string')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'recaptcha';
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }
}
