<?php
namespace Zdrw\OffersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
            ->add('longDesc', 'textarea')
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
