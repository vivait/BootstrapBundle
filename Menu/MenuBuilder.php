<?php

namespace Vivait\BootstrapBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Vivait\BootstrapBundle\Event\ConfigureMenuEvent;

class MenuBuilder {
	private $factory;

	private $event_dispatcher;

	/**
	 * @param FactoryInterface $factory
	 */
	public function __construct(FactoryInterface $factory, EventDispatcherInterface $event_dispatcher) {
		$this->factory = $factory;
		$this->event_dispatcher = $event_dispatcher;
	}

	public function createMainMenu(Request $request) {
		$menu = $this->factory->createItem('root', [
			'navbar' => true
		]);

		$this->event_dispatcher->dispatch(ConfigureMenuEvent::CONFIGURE, new ConfigureMenuEvent($this->factory, $menu));

		return $menu;
	}
}