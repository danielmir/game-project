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
     * @ORM\ManyToOne(targetEntity="GameProject\AdminBundle\Entity\Subdomain", inversedBy="categories")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     */
    protected $subdomain;

    /**
     * @ORM\ManyToMany(targetEntity="Game", mappedBy="categories")
     *
     */
    protected $games;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    public function __construct()
    {
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Add games
     *
     * @param \GameProject\GameBundle\Entity\Game $games
     * @return Category
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
}
