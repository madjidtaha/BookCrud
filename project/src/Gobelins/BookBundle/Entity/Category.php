<?php


namespace Gobelins\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Behavior;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * @author Madjid Taha <contact@madjidtaha.fr>
 *
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ExclusionPolicy("all")
 *
 */

class Category
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     *
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=50)
     * @Expose()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Gobelins\BookBundle\Entity\Book", mappedBy="category")
     * @Exclude()
     */
    private $books;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @param mixed $books
     */
    public function setBooks($books)
    {
        $this->books = $books;
    }



} 