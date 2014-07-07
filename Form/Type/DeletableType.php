<?php

namespace Vivait\BootstrapBundle\Form\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeletableType extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['delete_button']) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'addDeleteButton'));

            if (is_callable($options['delete_button'])) {
                $builder->addEventListener(
                  FormEvents::POST_SUBMIT,
                  function (FormEvent $event) use ($options) {
                      /* @var ClickableInterface $button */
                      $button = $event->getForm()->get('delete');
                      if ($button->isClicked()) {
                          call_user_func($options['delete_button'], $event);
                      }
                  }
                );
            }
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
          array(
            'delete_button' => false
          )
        );
    }

    public function getExtendedType()
    {
        return 'form';
    }

    public function addDeleteButton(FormEvent $event)
    {
        $form = $event->getForm();
        $object = $event->getData();

        if ($object && $object->getId()) {
            $form->add(
              'delete',
              'submit',
              [
                'label' => 'Delete',
                'attr' => [
                  'class' => 'btn-danger'
                ]
              ]
            );
        }
    }
}