<?php
namespace Vivait\BootstrapBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateSinceType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
//			$builder->addViewTransformer(new DateTimeToLocalizedStringTransformer(
//				$options['model_timezone'],
//				$options['view_timezone'],
//				$dateFormat,
//				$timeFormat,
//				$calendar,
//				$pattern
//			));

		$builder
			->add('since', 'text', array());
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
		));
	}

	public function getParent()
	{
		return 'date';
	}

	public function getName()
	{
		return 'datesince';
	}
}