<?php

namespace Zdrw\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Like
 *
 * @ORM\Table(name="likes")
 * @ORM\Entity
 */
class Like
{

    /**
     * @ORM\ManyToOne(targetEntity="Zdrw\OffersBundle\Entity\Offer", inversedBy="likes")
     **/

    protected $offer;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="likes")
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set offer
     *
     * @param \Zdrw\OffersBundle\Entity\Offer $offer
     * @return Like
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
     * @return Like
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
