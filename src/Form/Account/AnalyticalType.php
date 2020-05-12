<?php

namespace App\Form\Account;

use App\Entity;
use App\Requisition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as NativeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnalyticalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    )
    {
        $builder
            ->add(
                'id',
                NativeType\HiddenType::class
            )
            ->add('numeral', NativeType\TextType::class)
            ->add('name', NativeType\TextType::class)
            ->add(
                'account',
                EntityType::class,
                [
                    'class' => Entity\Account::class,
                    'choice_label' => 'name',
                    'error_bubbling' => false
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Requisition\Account\Analytical::class,
        ]);
    }
}
