<?php
// src/Acme/UserBundle/Entity/User.php

namespace Zdrw\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\OneToMany(targetEntity="Zdrw\OffersBundle\Entity\Notification", mappedBy="user")
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="Zdrw\OffersBundle\Entity\Reward", mappedBy="user")
     */
    protected $rewards;

    /**
     * @ORM\OneToMany(targetEntity="Zdrw\OffersBundle\Entity\Offer", mappedBy="user")
     */
    protected $offers;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;

    /** @ORM\Column(name="points", type="integer") */
    protected $points = 0;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

    /** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
    protected $google_id;

    /** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
    protected $google_access_token;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->rewards = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->offers = new ArrayCollection();
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
     * Add notification
     *
     * @param \Zdrw\OffersBundle\Entity\Notification $notification
     * @return User
     */
    public function addNotification(\Zdrw\OffersBundle\Entity\Notification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \Zdrw\OffersBundle\Entity\Notification $notification
     */
    public function removeNotification(\Zdrw\OffersBundle\Entity\Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notification
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotification()
    {
        return $this->notifications;
    }

    /**
     * Add rewards
     *
     * @param \Zdrw\OffersBundle\Entity\Reward $rewards
     * @return User
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
     * Add offers
     *
     * @param \Zdrw\OffersBundle\Entity\Offer $offers
     * @return User
     */
    public function addOffer(\Zdrw\OffersBundle\Entity\Offer $offers)
    {
        $this->offers[] = $offers;

        return $this;
    }

    /**
     * Remove offers
     *
     * @param \Zdrw\OffersBundle\Entity\Offer $offers
     */
    public function removeOffer(\Zdrw\OffersBundle\Entity\Offer $offers)
    {
        $this->offers->removeElement($offers);
    }

    /**
     * Get offers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set google_id
     *
     * @param string $googleId
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get google_id
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set google_access_token
     *
     * @param string $googleAccessToken
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;

        return $this;
    }

    /**
     * Get google_access_token
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return User
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
}
