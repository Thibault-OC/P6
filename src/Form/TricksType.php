<?php

namespace App\Form;

use App\Entity\Tricks;
use App\Entity\Category;
use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
            'required' => true,
                ])
            ->add('content',null,[
                'required' => true,
            ])
            ->add('images', FileType::class, [
                'label' => 'Images',
                'multiple' => true,
                'mapped'=> false,
                'required'=>false,
                'attr'     => [
                    'accept' => 'image/*',
                   
                ],
            ])
            
            ->add('videos', CollectionType::class, array(
                'entry_type' => VideoType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'by_reference' => false,
            ))

            ->add('Category', EntityType::class, [
          // looks for choices from this entity
          'class' => Category::class,

          // uses the User.username property as the visible option string
          'choice_label' => 'name',

          // used to render a select box, check boxes or radios
          // 'multiple' => true,
          // 'expanded' => true,
      ])
        ->add('image', FileType::class, [
        'label' => 'Image',



        // unmapped means that this field is not associated to any entity property
        'mapped' => false,

        // make it optional so you don't have to re-upload the PDF file
        // every time you edit the Product details
        'required' => true,

        // unmapped fields can't define their validation using annotations
        // in the associated entity, so you can use the PHP constraint classes
        'constraints' => [
            new File([
                'maxSize' => '1600k',
                'mimeTypes' => [
                    "image/jpeg",
                    "image/png",
                    "image/gif",
                    "image/jpg"
                ],
                'mimeTypesMessage' => 'Please upload a valid image',
            ])
        ],
    ])


        ;

    }
  /*  public function buildFormVideo(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('video', FileType::class, [
                'label' => 'Videos',
                'data_class' => 'App\Entity\Video;',
                'multiple' => true,
                'attr'     => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple'
                ],
            ])
        ;

    }*/

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
