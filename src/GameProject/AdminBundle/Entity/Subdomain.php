<?php
// src/GameProject/AdminBundle/Entity/Subdomain.php
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
     * @ORM\OneToMany(targetEntity="GameProject\GameBundle\Entity\CategoryContent", mappedBy="subdomain")
     **/
    protected $category_contents;

    /**
     * @ORM\OneToMany(targetEntity="GameProject\GameBundle\Entity\GameContent", mappedBy="subdomain")
     **/
    protected $game_contents;

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
        $this->category_contents = new ArrayCollection();
        $this->game_content = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * Add category_contents
     *
     * @param \GameProject\GameBundle\Entity\CategoryContent $categoryContents
     * @return Subdomain
     */
    public function addCategoryContent(\GameProject\GameBundle\Entity\CategoryContent $categoryContents)
    {
        $this->category_contents[] = $categoryContents;

        return $this;
    }

    /**
     * Remove category_contents
     *
     * @param \GameProject\GameBundle\Entity\CategoryContent $categoryContents
     */
    public function removeCategoryContent(\GameProject\GameBundle\Entity\CategoryContent $categoryContents)
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

    /**
     * Add game_content
     *
     * @param \GameProject\GameBundle\Entity\GameContent $gameContent
     * @return Subdomain
     */
    public function addGameContent(\GameProject\GameBundle\Entity\GameContent $gameContent)
    {
        $this->game_content[] = $gameContent;

        return $this;
    }

    /**
     * Remove game_content
     *
     * @param \GameProject\GameBundle\Entity\GameContent $gameContent
     */
    public function removeGameContent(\GameProject\GameBundle\Entity\GameContent $gameContent)
    {
        $this->game_content->removeElement($gameContent);
    }

    /**
     * Get game_content
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGameContent()
    {
        return $this->game_content;
    }

    /**
     * Get game_contents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGameContents()
    {
        return $this->game_contents;
    }
}
