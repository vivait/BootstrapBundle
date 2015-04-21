<?php

namespace Vivait\BootstrapBundle\User;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
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
		/* @var $token TokenInterface */
		$token = $this->container->get('security.context')->getToken();
		return $token->getUser() ?: null;
	}
}