<?php

namespace Vivait\BootstrapBundle\User;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Vivait\Common\User\UserCallableInterface;

class UserCallable implements UserCallableInterface {
    /* @var ContainerInterface */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct( ContainerInterface $container ) {
        $this->container = $container;
    }

    /**
     * @{inheritdoc}
     */
    public function getCurrentUser() {

        /** @var SecurityContext $context */
        $context = $this->container->get('security.context');

        /** @var $token TokenInterface */
        $token = $context->getToken();

        if($token){
            return $token->getUser() ?: null;
        } else {
            return null;
        }
    }
}