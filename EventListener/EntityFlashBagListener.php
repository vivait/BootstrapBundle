<?php

namespace Vivait\BootstrapBundle\EventListener;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Vivait\Common\Event\EntityEvent;

class EntityFlashBagListener {
	/**
	 * @var SessionInterface
	 */
	private $session;

	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * @param SessionInterface $session
	 * @param TranslatorInterface $translator
	 */
	public function __construct(SessionInterface $session, TranslatorInterface $translator)
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