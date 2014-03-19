<?php

namespace Vivait\BootstrapBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class EntityEvent extends Event {
	const EVENT_ENTITY_MODIFIED = 'vivait.entity.modified';

	/**
	 * @var object
	 */
	protected $entity;

	/**
	 * @var bool
	 */
	protected $is_new = false;

	public function __construct($entity, $is_new = false)
	{
		$this->entity = $entity;
		$this->is_new = $is_new;
	}

	/**
	 * @return object
	 */
	public function getEntity()
	{
		return $this->entity;
	}

	public function getEntityName() {
		return 'entity';
	}

	/**
	 * Is the entity new?
	 * @return boolean
	 */
	public function isNew() {
		return $this->is_new;
	}
} 