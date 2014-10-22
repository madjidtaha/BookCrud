<?php

namespace Gobelins\NewsBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", "text", [
                "required"   => true,
                "label"      => "Titre",
                "max_length" => 50,
                "error_bubbling" => true
            ])
            ->add("content", "textarea", [
                "required" => true,
                "label"    => "Contenu",
                "error_bubbling" => true
            ])
            ->add("author", "entity", [
                'class'    => 'GobelinsUserBundle:User',
                'property' => 'email',
                "error_bubbling" => true
             ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Gobelins\NewsBundle\Entity\News'
        ]);
    }
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'news_form';
    }
}