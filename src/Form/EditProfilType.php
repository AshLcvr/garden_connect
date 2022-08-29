<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
            'label' => 'Photo de profil :',
            'label_attr' => [
                'class' => 'bold'
            ],
            'attr' => [
                'class' => ''
            ],
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                // 'multiple' => true,
                'constraints' => [
                    // new All([

                    new File([
                        'maxSize' => '10000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                    'mimeTypesMessage' => 'Seuls les formats PNG, JPEG ou GIF sont acceptés !',
                        'uploadIniSizeErrorMessage' => 'Votre fichier est trop volumineux !',
                        'uploadErrorMessage' => 'Erreur dans l\'ajout du fichier!',
                    'uploadExtensionErrorMessage' => 'Mauvaise extension !',
                        'uploadFormSizeErrorMessage' => 'Votre fichier est trop volumineux !',
                        'uploadNoFileErrorMessage' => 'Aucun fichier n\'a été enregistré !',
                    ])
                // ])
                ],
            ])
            ->add('name', TextType::class, [
            'label' => 'Prénom :',
            'label_attr' => [
                'class' => 'bold'
            ],
            'attr' => [
                'class' => ''
            ]
            ])
            ->add('surname', TextType::class, [
            'label' => 'Nom :',
            'label_attr' => [
                'class' => 'bold'
            ],
            'attr' => [
                'class' => ''
            ]
            ])
            ->add('email', EmailType::class, [
            'label' => 'E-mail :',
            'label_attr' => [
                'class' => 'bold'
            ],
            'attr' => [
                'class' => ''
            ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
