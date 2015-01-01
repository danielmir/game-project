<?php
// src/GameProject/GameBundle/Entity/GameContent.php
namespace GameProject\GameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="game_contents")
 */
class GameContent
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="game_contents")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     **/
    protected $game;

    /**
     * @ORM\ManyToMany(targetEntity="CategoryContent", inversedBy="game_contents")
     * @ORM\JoinTable(name="games_categories")
     */
    protected $category_contents;

    /**
     * @ORM\ManyToOne(targetEntity="GameProject\AdminBundle\Entity\Subdomain", inversedBy="game_contents")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     **/
    protected $subdomain;

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
     * Set name
     *
     * @param string $name
     * @return GameContent
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
     * @return GameContent
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
     * @return GameContent
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
     * Set linkDisplay
     *
     * @param string $linkDisplay
     * @return GameContent
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

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return GameContent
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
     * Set game
     *
     * @param Game $game
     * @return GameContent
     */
    public function setGame(Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Add category_contents
     *
     * @param CategoryContent $categoryContents
     * @return GameContent
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

    /**
     * Set subdomain
     *
     * @param \GameProject\AdminBundle\Entity\Subdomain $subdomain
     * @return GameContent
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
}
