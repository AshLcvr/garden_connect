<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Categories;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $categoryParent = $options['categoryParent'];

        $builder
            ->add('title',TextType::class, [
                'label' => 'Titre de l\'annonce',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('price',IntegerType::class, [
                'label' => 'Prix'
            ])
            ->add('upload', FileType::class,[
            'label' => 'Ajouter des images à votre annonce (4 max)',
            'mapped' => false,
            'required' => false,
            'data_class' => null,
            'multiple' => true,
            'constraints' => [
                new All([

                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Seuls les formats PNG, JPEG ou GIF sont acceptés !)',
                        'uploadIniSizeErrorMessage' => 'Votre fichier est trop volumineux !',
                        'uploadErrorMessage' => 'Erreur dans l \'ajout du fichier!',
                        'uploadExtensionErrorMessage' => 'Mauvaise Extension !',
                        'uploadFormSizeErrorMessage' => 'Votre fichier est trop volumineux !',
                        'uploadNoFileErrorMessage' => 'Aucun fichier n\a été enregistré !',
                    ])
                ])
            ],
        ])
        ->add('category', EntityType::class,[
            'class' =>  Category::class,
            'choice_label' => 'title',
            'mapped' => false,
            'query_builder' => function (EntityRepository $er) use ($categoryParent){
                return $er->createQueryBuilder('c')
                    ->where('c.parent_id = :parent_id')
                    ->setParameter('parent_id' , null)
                    ->orderBy('c.title', 'ASC')
;
             },
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
            'categoryParent' => null
            ]);}
}
