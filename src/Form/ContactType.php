<?php

namespace App\Form;

use App\Entity\Contact;
use App\Form\Transaction;
use App\Requisition;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as NativeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'id',
                NativeType\HiddenType::class
            )
            ->add('name', NativeType\TextType::class)
            ->add('address', NativeType\TextType::class)
            ->add('phone', NativeType\TextType::class)
            ->add('email', NativeType\TextType::class)
            ->add('registrationNumber', NativeType\TextType::class)
            ->add('isVatPayer', NativeType\CheckboxType::class)
            ->add('vatNumberPrefix', NativeType\TextType::class)
            ->add('vatNumber', NativeType\TextType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Requisition\Contact::class,
            ]
        );
    }
}
