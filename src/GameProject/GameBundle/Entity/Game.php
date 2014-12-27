<?php
// src/GameProject/GameBundle/Entity/Game.php
namespace GameProject\GameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="games")
 */
class Game
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="GameProject\AdminBundle\Entity\Subdomain", inversedBy="games")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     */
    protected $subdomain;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="games")
     */
    protected $categories;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $link;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $linkDisplay;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Game
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Game
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Game
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set subdomain
     *
     * @param \GameProject\AdminBundle\Entity\Subdomain $subdomain
     * @return Game
     */
    public function setSubdomain(\GameProject\AdminBundle\Entity\Subdomain $subdomain = null)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * Get subdomain
     *
     * @return \GameProject\AdminBundle\Entity\Subdomain
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Add categories
     *
     * @param \GameProject\GameBundle\Entity\Category $categories
     * @return Game
     */
    public function addCategory(\GameProject\GameBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \GameProject\GameBundle\Entity\Category $categories
     */
    public function removeCategory(\GameProject\GameBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set linkDisplay
     *
     * @param string $linkDisplay
     * @return Game
     */
    public function setLinkDisplay($linkDisplay)
    {
        $this->linkDisplay = $linkDisplay;

        return $this;
    }

    /**
     * Get linkDisplay
     *
     * @return string 
     */
    public function getLinkDisplay()
    {
        return $this->linkDisplay;
    }
}
