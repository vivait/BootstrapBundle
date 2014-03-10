<?php

namespace Vivait\BootstrapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Viva\ApolloBundle\Event\EntityEvent;

class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
	public function redirectBack(Request $request) {
		$current = $request->attributes->get('_route');
		$parent = $request->query->get('parent', $request->request->get('parent', $request->headers->get('referer')));

		return $this->render('VivaitBootstrapBundle:Default:redirect.html.twig', array(
			'redirect' => ($parent != $current) ? $parent : 'viva_app_homepage'
		));
	}

	public function onSuccess(EntityEvent $event) {
		$this->getFlashBag()->add('success', 'The entry has been ' . ($event->isNew() ? 'created' : 'modified') . ' successfully!');
	}

	public function onFailure() {
		$this->getFlashBag()->add('error', 'Could not find the item you are looking for, maybe someone has just deleted it?');
	}

	public function redirectAndFlash($entity) {
		$this->onSuccess(new EntityEvent($entity, true));
	}

	public function getFlashBag() {
		return $this->get('session')->getFlashBag();
	}
}
