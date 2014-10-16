<?php

namespace Acme\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
	public function getPosts(){
		return $this->getEntityManager()->createQuery("SELECT name FROM AcmeBlogBundle:Post name")->execute();
	}
}