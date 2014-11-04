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
}
