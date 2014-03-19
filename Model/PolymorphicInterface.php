<?php

namespace Vivait\BootstrapBundle\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

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
	 * This form will build the form depending on the requirements of the polymorphic class
	 * @param FormBuilder $form
	 * @return void
	 * @deprecated
	 */
	public function buildForm(FormBuilder $form);

	/**
	 * Returns an instance of the form type
	 * @return AbstractType
	 */
	public function getFormType();
}
