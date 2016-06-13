<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StandartRepository extends EntityRepository
{
    public function findForCategory($category = 0)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT p.id, p.title  FROM AppBundle:Standart p WHERE p.category = $category")
            ->getResult();
    }

    public function findByQuery($query)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('s.id, s.title')
            ->from('AppBundle:Standart', 's')
            ->orderBy('s.id', 'ASC');

        $words = explode(' ', $query);

        foreach ($words as $word) {
            $qb->andWhere("s.title LIKE '$word%' OR s.body LIKE '$word%' OR s.title LIKE '% $word%' OR s.body LIKE '% $word%'");
        }

        return $qb->getQuery()->getResult();
    }
}