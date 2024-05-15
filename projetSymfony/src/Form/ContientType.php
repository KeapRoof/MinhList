<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Contient;
use App\Entity\Liste;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('Acheter')
            ->add('Dans', EntityType::class, [
                'class' => Liste::class,
                'choice_label' => 'id',
            ])
            ->add('Contenue', EntityType::class, [
                'class' => Article::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contient::class,
        ]);
    }
}
