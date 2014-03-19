<?php

	namespace Vivait\BootstrapBundle\Model;

	interface PolymorphicInterface {

		/**
		 * These methods should be implemented on the "parent" polymorphic objects
		 */

		/**
		 * Returns all objects that are used in this polymorphic association
		 * @return PolymorphicInterface[]
		 */
		public static function generateAllPolyObjects();

		/**
		 * Generate a new polymorphic object from a specified service alias
		 * @param $poly_alias
		 * @return PolymorphicInterface
		 */
		public static function generatePolyObject($poly_alias);

		/**
		 * These methods should be implemented on the "child" polymorphic objects
		 */

		/**
		 * Returns a unique service/form alias to be used for business logic and form generation.
		 * Aliases returned here should be found in the service container
		 * @return string
		 */
		public function getPolyAlias();

		/**
		 * Returns a label that can be used in selection dialogs
		 * @return string
		 */
		public function getPolyLabel();

		/**
		 * Returns a short description that can be used in selection dialogs
		 * @return string
		 */
		public function getPolyDescription();

	}
