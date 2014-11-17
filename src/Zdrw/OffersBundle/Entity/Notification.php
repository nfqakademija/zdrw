<?php

namespace Zdrw\OffersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="Zdrw\OffersBundle\Entity\NotificationRepository")
 */
class Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="Zdrw\UserBundle\Entity\User", inversedBy="notifications")
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
     * @var string
     *
     * @ORM\Column(name="notification", type="text")
     */
    private $notification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="seen", type="integer")
     */
    private $seen;


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
     * Set notification
     *
     * @param string $notification
     * @return Notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return string
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Notification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set seen
     *
     * @param integer $seen
     * @return Notification
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return integer
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set user
     *
     * @param \Zdrw\UserBundle\Entity\User $user
     * @return Notification
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
