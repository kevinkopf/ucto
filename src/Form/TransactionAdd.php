<?php

namespace App\Form;

use App\Entity\Contact;
use App\Requisition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as NativeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TransactionAdd extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taxableSupplyDate', NativeType\DateType::class)
            ->add(
                'contact',
                EntityType::class,
                [
                    'class' => Contact::class,
                    'choice_label' => 'name',
                    'error_bubbling' => false,
                ]
            )
            ->add('description', NativeType\TextType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Requisition\TransactionAdd::class,
            ]
        );
    }
}
