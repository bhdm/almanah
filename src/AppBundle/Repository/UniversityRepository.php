<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UniversityRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(),array('title'=> 'ASC'));
    }

	public function arrangeByCountry($countryId)
	{
		$byCountry = $this->_em->createQuery('
			SELECT u.id, u.title
			FROM AppBundle:University u
			WHERE u.country = :countryId
			ORDER BY u.title ASC
		')->setParameter('countryId', $countryId)
			->getResult();

		$notCountry = $this->_em->createQuery('
			SELECT u.id, u.title
			FROM AppBundle:University u
			WHERE u.country != :countryId
			ORDER BY u.title ASC
		')->setParameter('countryId', $countryId)
			->getResult();

		return array_merge($byCountry, $notCountry);
	}

	public function findForAutocomplete($title = null){
		$qb = $this->createQueryBuilder('u')
			->select('u.title');
			if ($title){
				$qb->where("u.title LIKE '%$title%'");
			}

		return $qb->getQuery()->getResult();
	}
}