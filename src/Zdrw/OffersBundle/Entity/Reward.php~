<?php

namespace Zdrw\OffersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reward
 *
 * @ORM\Table(name="rewards")
 * @ORM\Entity(repositoryClass="Zdrw\OffersBundle\Entity\RewardRepository")
 */
class Reward
{

    /**
     * @ORM\ManyToOne(targetEntity="Offer", inversedBy="rewards")
     **/

    protected $offer;

    /**
     * @ORM\ManyToOne(targetEntity="Zdrw\UserBundle\Entity\User", inversedBy="rewards")
     **/
    protected $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;


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
     * Set points
     *
     * @param integer $points
     * @return Reward
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set offer
     *
     * @param \Zdrw\OffersBundle\Entity\Offer $offer
     * @return Reward
     */
    public function setOffer(\Zdrw\OffersBundle\Entity\Offer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return \Zdrw\OffersBundle\Entity\Offer 
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set user
     *
     * @param \Zdrw\UserBundle\Entity\User $user
     * @return Reward
     */
    public function setUser(\Zdrw\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Zdrw\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
