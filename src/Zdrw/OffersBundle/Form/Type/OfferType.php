<?php
namespace Zdrw\OffersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('attr' => array('maxlength' => 50)))
            ->add('description', 'textarea', array('attr' => array('maxlength' => 500)))
            ->add('longDesc', 'textarea', array('attr' => array('maxlength' => 1500)))
            ->add('rewards', 'text', array(
                'mapped' => false
                ))
            ->add('categoryId', 'hidden', array('data' => 1))
            ->add('status', 'hidden', array('data' => 1))
            ->add('views', 'hidden', array('data' => 1))
            ->add('participantId', 'hidden', array('data' => 1))
            ->add('save', 'submit', array('label' => 'Create Dare'))
            ->getForm();
    }

    public function getName()
    {
        return 'offer';
    }
}
