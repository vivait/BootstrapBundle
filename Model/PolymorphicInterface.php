<?php

namespace Vivait\BootstrapBundle\Model;

interface PolymorphicInterface
{
	/**
	 * @return array
	 */
	public static function getAllTypes();

	/**
	 * @param $typeid
	 * @return self
	 */
	public static function getObjectFromTypeID($typeid);

	#can't use abstract static functions for these due to PHP late static binding issues

	/**
	 * @return string
	 */
	public function getTypeName();

	/**
	 * @return string
	 */
	public function getTypeDescription();

	/**
	 * @return integer
	 */
	public function getTypeID();

	/**
	 * Returns an instance of the form type
	 * @return \Symfony\Component\Form\AbstractType
	 */
	public function getFormType();
}
