<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/post/create')
            ->setMethod('POST')
            ->add('title', TextType::class, array(
                'label' => false,
                'attr'   =>  array(
                    'placeholder'   => 'Do you wanna post with a title? Type here!',
                    'data-min-file-count' => '1'
                    ),
                'required' => false
                )
            )
            ->add('imgName', FileType::class, array(
                'label' => false,
                'attr'   =>  array(
                    'class'   => 'file',
                    'data-min-file-count' => '1'
                    )
                )
            )
            ->add('save', SubmitType::class, array('label' => 'Publish'))
            ->getForm();
        ;
    }
}
