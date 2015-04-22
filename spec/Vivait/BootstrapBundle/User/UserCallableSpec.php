<?php

namespace spec\Vivait\BootstrapBundle\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCallableSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\BootstrapBundle\User\UserCallable');
    }

    function let(ContainerInterface $container)
    {
        $this->beConstructedWith($container);
    }

    function it_returns_the_current_user(ContainerInterface $container, SecurityContextInterface $context, TokenInterface $token, UserInterface $user)
    {
        $token->getUser()->willReturn($user);
        $context->getToken()->willReturn($token);
        $container->get('security.context')->willReturn($context);

        $this->getCurrentUser()->shouldReturn($user);
    }

    function it_returns_null_if_no_token_set(ContainerInterface $container, SecurityContextInterface $context)
    {
        $context->getToken()->willReturn(null);
        $container->get('security.context')->willReturn($context);

        $this->getCurrentUser()->shouldReturn(null);
    }

    function it_returns_null_if_no_user(ContainerInterface $container, SecurityContextInterface $context, TokenInterface $token)
    {
        $token->getUser()->willReturn(null);
        $context->getToken()->willReturn($token);
        $container->get('security.context')->willReturn($context);

        $this->getCurrentUser()->shouldReturn(null);
    }

    function it_returns_null_if_user_is_string(ContainerInterface $container, SecurityContextInterface $context, TokenInterface $token)
    {
        $token->getUser()->willReturn('anon');
        $context->getToken()->willReturn($token);
        $container->get('security.context')->willReturn($context);

        $this->getCurrentUser()->shouldReturn(null);
    }
}
