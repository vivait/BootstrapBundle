<?php
namespace Vivait\BootstrapBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class DateSinceType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

	}

	/**
	 * {@inheritdoc}
	 */
	public function finishView(FormView $view, FormInterface $form, array $options)
	{
		if ('single_text' === $options['widget']) {
			$view->vars['date_moment_pattern'] = strtoupper($options['format']);
		}
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'horizontal_input_wrapper_class' => ''
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