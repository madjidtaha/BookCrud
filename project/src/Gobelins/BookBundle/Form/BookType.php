<?php

namespace Gobelins\BookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class BookType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", "text", [
                "required" => true,
                "label" => "Nom",
                "max_length" => 50,
                "error_bubbling" => true
            ])
            ->add("publishedAt", "date", [
                "required" => true,
                "label"    => "Publié le",
                "error_bubbling" => true
            ])
            ->add("description", "textarea", [
                "required" => true,
                "label"    => "Contenu",
                "error_bubbling" => true
            ])
            ->add("price", "money",  [
                "required" => true,
                "label"    => "Prix",
                "error_bubbling" => true
            ])
            ->add("category", "entity",  [
                "class"    => "GobelinsBookBundle:Category",
                "property" => "name",
                "required" => true,
                "label"    => "Categorie",
                "error_bubbling" => true
            ]);
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Gobelins\BookBundle\Entity\Book'
        ]);
    }
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'book_form';
    }
} 