<?php

	namespace Vivait\BootstrapBundle\Model;

	/**
	 * Class PolymorphicTrait
	 * @mixin PolymorphicInterface
	 * @package Vivait\BootstrapBundle\Model
	 */
	trait PolymorphicTrait {
		/**
		 * @inheritDoc
		 */
		public static function generatePolyObject($poly_alias) {
			$objs = self::generateAllPolyObjects();
			foreach($objs as $obj) {
				if($obj->getPolyAlias() == $poly_alias) {
					return $obj;
				}
			}
			return null;
		}

	}
