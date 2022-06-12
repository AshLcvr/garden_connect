<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Subcategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('q', TextType::class, [
               'label' => false,
               'required'=> false,
                'attr' => [
                    'placeholder' => 'Mots clés'
                ]
           ])
           ->add('category', EntityType::class, [
               'label' => false,
               'required' => false,
               'class' => Category::class,
               'choice_label' => 'title',
               'placeholder' => 'Catégories',
           ])
           ->add('min', NumberType::class, [
               'label' => false,
               'required' => false,
               'attr' => [
                   'placeholder' => 'Prix min'
               ]
           ])
           ->add('max', NumberType::class, [
               'label' => false,
               'required' => false,
               'attr' => [
                   'placeholder' => 'Prix max'
               ]
           ])
           ->add('submit', SubmitType::class,[
               'label' => 'Rechercher'
           ])
       ;

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
                $subcat = $data->subcategory;
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
            'label' =>  false ,
            'placeholder' => $category? 'Type de produit' : 'Sous-catégories',
            'choices' => $category? $category->getSubcategories() : [],
            'choice_label' => 'title',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
           'data_class' => SearchData::class,
           'method' => 'GET',
           'crsf_protection' => false
       ]);
    }

    public function getBlockPrefix()
    {
       return '';
    }
}