<?php

namespace Zdrw\OffersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offer
 *
 * @ORM\Table(name="offers")
 * @ORM\Entity(repositoryClass="Zdrw\OffersBundle\Entity\OfferRepository")
 */
class Offer
{

    /**
     * @ORM\OneToMany(targetEntity="Reward", mappedBy="offer")
     */
    protected $rewards;

    /**
     * @ORM\ManyToOne(targetEntity="Zdrw\UserBundle\Entity\User", inversedBy="offers")
     **/
    protected $owner;

    /**
     * @ORM\ManyToOne(targetEntity="Zdrw\UserBundle\Entity\User", inversedBy="offers")
     **/
    protected $participant;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="long_desc", type="text")
     */
    private $longDesc;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer")
     */
    private $categoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255, nullable=true)
     */
    private $video = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finish_date", type="datetime", nullable=true)
     */
    private $finishDate = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views;

    /**
     * @var integer
     *
     * @ORM\Column(name="participant_id", type="integer", nullable=true)
     */
    private $participantId = null;



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
     * Set title
     *
     * @param string $title
     * @return Offer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Offer
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
     * Set categoryId
     *
     * @param integer $categoryId
     * @return Offer
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Offer
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set finishDate
     *
     * @param \DateTime $finishDate
     * @return Offer
     */
    public function setFinishDate($finishDate)
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * Get finishDate
     *
     * @return \DateTime
     */
    public function getFinishDate()
    {
        return $this->finishDate;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Offer
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }


    /**
     * Set participantId
     *
     * @param integer $participantId
     * @return Offer
     */
    public function setParticipantId($participantId)
    {
        $this->participantId = $participantId;

        return $this;
    }

    /**
     * Get participantId
     *
     * @return integer
     */
    public function getParticipantId()
    {
        return $this->participantId;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->startDate = new \DateTime();
        $this->rewards = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add rewards
     *
     * @param \Zdrw\OffersBundle\Entity\Reward $rewards
     * @return Offer
     */
    public function addReward(\Zdrw\OffersBundle\Entity\Reward $rewards)
    {
        $this->rewards[] = $rewards;

        return $this;
    }

    /**
     * Remove rewards
     *
     * @param \Zdrw\OffersBundle\Entity\Reward $rewards
     */
    public function removeReward(\Zdrw\OffersBundle\Entity\Reward $rewards)
    {
        $this->rewards->removeElement($rewards);
    }

    /**
     * Get rewards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    /**
     * Set owner
     *
     * @param \Zdrw\UserBundle\Entity\User $owner
     * @return Offer
     */
    public function setOwner(\Zdrw\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Zdrw\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set participant
     *
     * @param \Zdrw\UserBundle\Entity\User $participant
     * @return Offer
     */
    public function setParticipant(\Zdrw\UserBundle\Entity\User $participant = null)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get participant
     *
     * @return \Zdrw\UserBundle\Entity\User 
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set longDesc
     *
     * @param string $longDesc
     * @return Offer
     */
    public function setLongDesc($longDesc)
    {
        $this->longDesc = $longDesc;

        return $this;
    }

    /**
     * Get longDesc
     *
     * @return string 
     */
    public function getLongDesc()
    {
        return $this->longDesc;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Offer
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set video
     *
     * @param string $video
     * @return Offer
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }
}
