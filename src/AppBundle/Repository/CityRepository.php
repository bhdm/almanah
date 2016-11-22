<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
	public function findForAutocomplete($title = null){
		$qb = $this->createQueryBuilder('c')
			->select('c.title');
			if ($title){
				$qb->where("c.title LIKE '%$title%'");
			}
        $qb->setMaxResults(7);
		return $qb->getQuery()->getResult();
	}
}