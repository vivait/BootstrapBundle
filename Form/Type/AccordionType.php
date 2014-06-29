<?php
namespace Vivait\BootstrapBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccordionType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
		));
	}

	public function getParent()
	{
		return 'collection';
	}

	public function getName()
	{
		return 'accordion';
	}
}