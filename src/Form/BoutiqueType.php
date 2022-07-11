<?php

namespace App\Form;

use App\Entity\Boutique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class BoutiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'label'    => 'Titre de la boutique'
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('upload', FileType::class,[
                'label'       => 'Ajouter des images à votre boutique',
                'mapped'      => false,
                'required'    => false,
                'data_class'  => null,
                'multiple'    => true,
                'constraints' => [
                    new All([

                        new File(['maxSize' => '5000k',
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
            ->add('indicatif',ChoiceType::class,[
                'mapped'  => false,
                'choices' => [
                    '+33' => '+33'
                ]
            ])
            ->add('telephone',TelType::class,[
                'required' => false,
                'trim'     => false,
            ])
            ->add('adress',TextType::class,[
                'required' => false,
                'label'    => 'N° et nom de voie',
            ])
            ->add('search', TextType::class,[
                'mapped'      => false,
                'required'    => true,
                'label'       => 'Ville ou Code Postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner ce champ'
                    ])
                ]
            ])
            ->add('city', HiddenType::class)
            ->add('postcode', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boutique::class,
        ]);
    }
}
