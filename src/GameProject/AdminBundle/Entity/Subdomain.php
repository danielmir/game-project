<?php
// src/GameProject/AdminBundle/Entity/Category.php
namespace GameProject\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subdomains")
 * @ORM\HasLifecycleCallbacks
 */
class Subdomain
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="GameProject\GameBundle\Entity\Category", mappedBy="subdomain")
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="GameProject\GameBundle\Entity\Game", mappedBy="subdomain")
     */
    protected $games;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $abbreviation;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->games = new ArrayCollection();
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
     * @return Subdomain
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return Subdomain
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
     * Add categories
     *
     * @param \GameProject\GameBundle\Entity\Category $categories
     * @return Subdomain
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
     * Add games
     *
     * @param \GameProject\GameBundle\Entity\Game $games
     * @return Subdomain
     */
    public function addGame(\GameProject\GameBundle\Entity\Game $games)
    {
        $this->games[] = $games;

        return $this;
    }

    /**
     * Remove games
     *
     * @param \GameProject\GameBundle\Entity\Game $games
     */
    public function removeGame(\GameProject\GameBundle\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get games
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Set abbreviation
     *
     * @param string $abbreviation
     * @return Subdomain
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string 
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    public function __toString()
    {
        return $this->name;
    }
}
