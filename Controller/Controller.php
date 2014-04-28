<?php

namespace Vivait\BootstrapBundle\Controller;

use Doctrine\ORM\UnitOfWork;
use Symfony\Component\HttpFoundation\Request;
use Vivait\Common\Event\EntityEvent;

class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
	/**
	 * @param Request $request
	 * @param bool $avoid_loop Avoid redirecting back to the current page
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function redirectBack(Request $request, $avoid_loop = false) {
		$current = $request->attributes->get('_route');
		$parent = $request->query->get('parent', $request->request->get('parent', $request->headers->get('referer')));

		if (!$parent || ($avoid_loop && $parent == $current)) {
			$parent = $this->generateUrl('viva_app_homepage');
		}

		return $this->redirectTo($request, $parent);
	}

	public function redirectTo(Request $request, $to) {
		if ($request->headers->get('X-REQUESTED-WITH') == 'XMLHttpRequest') {
			return $this->render('VivaitBootstrapBundle:Default:redirect.html.twig', array(
				'redirect' => $to
			));
		}

		return $this->redirect($to);
	}

	public function dispatchEntityEvent($events) {
		$em         = $this->getDoctrine()->getManager();
		$dispatcher = $this->get('event_dispatcher');

		/* @var $uow \Doctrine\ORM\UnitOfWork */
		$uow = $em->getUnitOfWork();
		$uow->computeChangeSets();

		if (is_array($events)) {
			// Dispatch each event
			array_walk($events, function(EntityEvent $event) use ($uow, $dispatcher) {
				$this->dispatchEntityEventRunner($event, $uow, $dispatcher);
			});
		}
		else {
			$this->dispatchEntityEventRunner($events, $uow, $dispatcher);
		}
	}

	protected function dispatchEntityEventRunner(EntityEvent $event, UnitOfWork $uow, $dispatcher = null) {
		if (!$dispatcher) {
			$dispatcher = $this->get('event_dispatcher');
		}

		if ($uow->isScheduledForUpdate($event->getEntity())) {
			$dispatcher->dispatch(EntityEvent::EVENT_ENTITY_MODIFIED, $event);
		}
		else if ($uow->isScheduledForInsert($event->getEntity())) {
			$dispatcher->dispatch(EntityEvent::EVENT_ENTITY_CREATED, $event);
		}
		else if ($uow->isScheduledForDelete($event->getEntity())) {
			$dispatcher->dispatch(EntityEvent::EVENT_ENTITY_DELETED, $event);
		}
	}
}
