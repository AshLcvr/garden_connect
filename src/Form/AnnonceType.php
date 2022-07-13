<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Categories;
use App\Entity\Category;
use App\Entity\Mesure;
use App\Entity\Subcategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class AnnonceType
 * @package App\Form
 */
class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'label' => 'Titre de l\'annonce',
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('price',IntegerType::class, [
                'label' => 'Prix',
                'required' => false
            ])
            ->add('mesure',EntityType::class, [
                'class' => Mesure::class,
                'choice_label' => 'title'
            ])
            ->add('upload', FileType::class,[
            'label' => 'Ajoutez des images à votre annonce (4 max)',
            'mapped' => false,
            'required' => false,
            'data_class' => null,
            'multiple' => true,
            'constraints' => [
                new All([

                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Seuls les formats PNG, JPEG ou GIF sont acceptés !)',
                        'uploadIniSizeErrorMessage' => 'Votre fichier est trop volumineux !',
                        'uploadErrorMessage' => 'Erreur dans l \'ajout du fichier !',
                        'uploadExtensionErrorMessage' => 'Mauvaise extension !',
                        'uploadFormSizeErrorMessage' => 'Votre fichier est trop volumineux !',
                        'uploadNoFileErrorMessage' => 'Aucun fichier n\a été enregistré !',
                    ])
                ])
            ],
        ])
        ->add('category', EntityType::class,[
            'label' => 'Sélectionnez une catégorie',
            'placeholder' => 'Catégorie de produit',
            'class' =>  Category::class,
            'choice_label' => 'title',
            'mapped' => false,
            'required' => false,
        ]);

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event){
                $form = $event->getForm()->getParent();
                $category = $event->getForm()->getData();
                $this->addSubCategoryField($form, $category);
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event){
                $data = $event->getData();
                $form = $event->getForm();
                /* @var $subcat Subcategory */
                $subcat = $data->getSubcategory();
                if($subcat){
                    $category = $subcat->getParentCategory();
                    $this->addSubCategoryField($event->getForm(), $category);
                    $form->get('category')->setData($category);
                    $form->get('subcategory')->setData($subcat);
                }else{
                    $this->addSubCategoryField($event->getForm(), null);
                }
        });
    }

    /**
     * @param FormInterface $form
     * @param Category $category
     */
    private function addSubCategoryField(FormInterface $form, ?Category $category)
    {
        $form->add('subcategory',EntityType::class,[
            'class' => Subcategory::class,
            'label' =>  'Sélectionnez votre type de produit' ,
            'placeholder' => $category? 'Type de produit' : 'Sélectionnez une catégorie',
            'choices' => $category? $category->getSubcategories() : [],
            'choice_label' => 'title',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
            ]);}
    }
