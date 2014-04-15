<?php

namespace Vivait\BootstrapBundle\EventListener;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\Translator;
use Vivait\Common\Event\EntityEvent;

class EntityFlashBagListener {
	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var Translator
	 */
	private $translator;

	/**
	 * @param Session $session
	 * @param Translator $translator
	 */
	public function __construct(Session $session, Translator $translator)
	{
		$this->session    = $session;
		$this->translator = $translator;
	}

	/**
	 * @param \Vivait\Common\Event\EntityEvent $event
	 */
	public function onEntityModifiedEvent(EntityEvent $event)
	{
		$this->getFlashBag()->add('success', $this->translator->trans('The %entity_name% has been modified successfully!', [
			'%entity_name%' => $event->getEntityName()
		]));
	}

	/**
	 * @param \Vivait\Common\Event\EntityEvent $event
	 */
	public function onEntityCreatedEvent(EntityEvent $event)
	{
		$this->getFlashBag()->add('success', $this->translator->trans('The %entity_name% has been created successfully!', [
			'%entity_name%' => $event->getEntityName()
		]));
	}

	/**
	 * @param \Vivait\Common\Event\EntityEvent $event
	 */
	public function onEntityDeletedEvent(EntityEvent $event)
	{
		$this->getFlashBag()->add('success', $this->translator->trans('The %entity_name% has been deleted', [
			'%entity_name%' => $event->getEntityName()
		]));
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
	 */
	private function getFlashBag() {
		return $this->session->getFlashBag();
	}
}