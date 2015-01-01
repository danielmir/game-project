<?php
// src/GameProject/GameBundle/Entity/CategoryContent.php
namespace GameProject\GameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="category_contents")
 */
class CategoryContent
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="category_contents")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="GameProject\AdminBundle\Entity\Subdomain", inversedBy="category_contents")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     **/
    protected $subdomain;

    /**
     * @ORM\ManyToMany(targetEntity="GameContent", mappedBy="category_contents")
     *
     */
    protected $game_contents;

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
        $this->game_contents = new ArrayCollection();
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
     * @return CategoryContent
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
     * @return CategoryContent
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
     * Set category
     *
     * @param \GameProject\GameBundle\Entity\Category $category
     * @return CategoryContent
     */
    public function setCategory(\GameProject\GameBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \GameProject\GameBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set subdomain
     *
     * @param \GameProject\AdminBundle\Entity\Subdomain $subdomain
     * @return CategoryContent
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
     * Add game_contents
     *
     * @param \GameProject\GameBundle\Entity\GameContent $gameContents
     * @return CategoryContent
     */
    public function addGameContent(\GameProject\GameBundle\Entity\GameContent $gameContents)
    {
        $this->game_contents[] = $gameContents;

        return $this;
    }

    /**
     * Remove game_contents
     *
     * @param \GameProject\GameBundle\Entity\GameContent $gameContents
     */
    public function removeGameContent(\GameProject\GameBundle\Entity\GameContent $gameContents)
    {
        $this->game_contents->removeElement($gameContents);
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
