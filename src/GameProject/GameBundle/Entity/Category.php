<?php
// src/GameProject/GameBundle/Entity/Category.php
namespace GameProject\GameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $displayName;

    /**
     * @ORM\OneToMany(targetEntity="CategoryContent", mappedBy="category")
     */
    protected $category_contents;


    public function __construct()
    {
        $this->category_contents = new ArrayCollection();
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Category
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Add category_contents
     *
     * @param CategoryContent $categoryContents
     * @return Category
     */
    public function addCategoryContent(CategoryContent $categoryContents)
    {
        $this->category_contents[] = $categoryContents;

        return $this;
    }

    /**
     * Remove category_contents
     *
     * @param CategoryContent $categoryContents
     */
    public function removeCategoryContent(CategoryContent $categoryContents)
    {
        $this->category_contents->removeElement($categoryContents);
    }

    /**
     * Get category_contents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategoryContents()
    {
        return $this->category_contents;
    }
}
