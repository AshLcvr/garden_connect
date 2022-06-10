<?php

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $categoryParent = $options['categoryParent'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('image', TextType::class, [
                'label' => 'Image'
            ])
            ->add('parent', EntityType::class,[
                'label' => 'Sélectionner un parent si besoin',
                'class' =>  Category::class,
                'choice_label' => 'title',
                'mapped' => false,
                'query_builder' => function (EntityRepository $er) use ($categoryParent){
                    return $er->createQueryBuilder('c')
                        ->where('c.parent_id is NULL')
                        ->orderBy('c.title', 'ASC');
                 },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'categoryParent' => null
        ]);
    }
}
