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
	 * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
	 */
	public function __construct(FactoryInterface $factory, EventDispatcherInterface $event_dispatcher) {
		$this->factory = $factory;
		$this->event_dispatcher = $event_dispatcher;
	}

	public function createMainMenu(Request $request) {
		$menu = $this->factory->createItem('root');

		$menu->addChild('main', [
			'navbar' => true
		]);

		// TODO: Move me to a listener so I can be ordered/prioritised
		$menu->addChild('search', [
			'navbar' => true,
			'pull-right' => true
		])->setExtra('template', 'VivaitBootstrapBundle:Default:search.html.twig');

		$this->event_dispatcher->dispatch(ConfigureMenuEvent::CONFIGURE, new ConfigureMenuEvent($this->factory, $menu));


		return $menu;
	}
}