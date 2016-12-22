<?php

namespace Vivait\BootstrapBundle\Controller;

use Doctrine\ORM\UnitOfWork;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Viva\AuthBundle\Entity\User;
use Vivait\Common\Event\EntityEvent;

class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
	
	/**
	 * @param Request $request
	 * @param bool $avoidLoop Avoid redirecting back to the current page
     *
	 * @return RedirectResponse|Response
	 */
	protected function redirectBack(Request $request, $avoidLoop = false)
    {
		$current = $request->attributes->get('_route');
		$parent = $request->query->get('parent', $request->request->get('parent', $request->headers->get('referer')));

		if ( ! $parent || ($avoidLoop && $parent == $current)) {
			$parent = $this->generateUrl('viva_app_homepage');
		}

		return $this->redirectTo($request, $parent);
	}

    /**
     * @param Request $request
     * @param string  $to
     *
     * @return mixed
     */
    protected function redirectTo(Request $request, $to)
    {
		if ($request->headers->get('X-REQUESTED-WITH') == 'XMLHttpRequest') {
			return $this->render('VivaitBootstrapBundle:Default:redirect.html.twig', array(
				'redirect' => $to
			));
		}

		return $this->redirect($to);
	}

    /**
     * @param mixed $events
     */
    protected function dispatchEntityEvent($events)
    {
		$em         = $this->getDoctrine()->getManager();
		$dispatcher = $this->get('event_dispatcher');

		/**
         * @var $uow \Doctrine\ORM\UnitOfWork
         */
		$uow = $em->getUnitOfWork();
		$uow->computeChangeSets();

		if (is_array($events)) {
			// Dispatch each event
			array_walk($events, function(EntityEvent $event) use ($uow, $dispatcher) {
				$this->dispatchEntityEventRunner($event, $uow, $dispatcher);
			});
		} else {
			$this->dispatchEntityEventRunner($events, $uow, $dispatcher);
		}
	}

    /**
     * @param EntityEvent $event
     * @param UnitOfWork  $uow
     * @param null        $dispatcher
     */
	protected function dispatchEntityEventRunner(EntityEvent $event, UnitOfWork $uow, $dispatcher = null)
    {
		if ( ! $dispatcher) {
			$dispatcher = $this->get('event_dispatcher');
		}

		if ($uow->isScheduledForUpdate($event->getEntity())) {
			$dispatcher->dispatch($event::EVENT_ENTITY_MODIFIED, $event);
		} else if ($uow->isScheduledForInsert($event->getEntity())) {
			$dispatcher->dispatch($event::EVENT_ENTITY_CREATED, $event);
		} else if ($uow->isScheduledForDelete($event->getEntity())) {
			$dispatcher->dispatch($event::EVENT_ENTITY_DELETED, $event);
		}
	}

    /**
     * @return User
     */
    protected function getUser()
    {
        return parent::getUser();
    }
}
