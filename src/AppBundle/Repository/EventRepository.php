<?php

namespace AppBundle\Repository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll()
    {
        return parent::findBy(['enabled' => true]);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (!isset($criteria['enabled'])){
            $criteria['enabled'] = true;
        }
        return parent::findBy($criteria, $orderBy, $limit, $offset); // TODO: Change the autogenerated stub
    }

    public function findEvent($owner, $dateStart,$dateEnd, array $params = [], $returnArray = false){
//        dump($dateStart);
//        dump($dateEnd);
//        exit;
        if ($returnArray == false){
            $dateEnd->modify('+2 month');
        }
        $qb = $this->createQueryBuilder('e');
        if ($returnArray === true){
            $qb->select('e.id , e.type, e.title, e.start, e.end');
        }else{
            $qb->select('e');
        }
        $qb->where('e.end >= :dateStart')
            ->andWhere('e.start <= :dateEnd')
            ->setParameters([
                'dateStart' => $dateStart,
                'dateEnd' => $dateEnd
            ]);
        if (isset($owner) && $owner === 'Partner'){
            $qb->andWhere("e.type = 'PARTNER'");
        }else{
            $qb->andWhere("e.type != 'PARTNER'");
        }
        $qb->orderBy('e.start');

//        $result = $qb->getQuery()->getSQL();
//        echo  $result;
//        exit;
        $qb->getMaxResults(5);
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function search($query){
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->where("s.title LIKE '%$query%'")
            ->orWhere("s.body  LIKE '%$query%' ")
            ->andWhere("s.enabled = 1")
            ->orderBy('s.created', 'DESC');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function searchEvents($category, $params){
        $qb = $this->createQueryBuilder('c')
            ->select('c');

        if ($category){
            $qb->where("c.category = :category");
            $qb->setParameter(':category', $category->getId());
        }

        if ( $params != null ){
            if (isset($params['start']) && $params['start'] != null){
                $qb->andWhere("c.start = :start");
                $qb->setParameter(':start', $params['start']);
            }
            if (isset($params['end']) && $params['end'] != null){
                $qb->andWhere("c.end = :end");
                $qb->setParameter(':end', $params['end']);
            }
            if (isset($params['specialty']) && $params['specialty'] != null){
                $qb->leftJoin('c.specialties', 's');
                $qb->andWhere("s.id = :specialty");
                $qb->setParameter(':specialty', $params['specialty']);
            }
            if (isset($params['search']) && $params['search'] != null){
                $qb->andWhere("c.title LIKE '%:search%'");
                $qb->orWhere("c.body LIKE '%:search%'");
                $qb->setParameter(':search', $params['search']);
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function filter($type,$start,$end,$text,$specialty){
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->leftJoin('s.specialties', 'spec');
        $qb->where('s.enabled = 1');
        if ($type != null){
            $qb->andWhere('s.category = :type');
            $qb->setParameter('type', $type);
        }

        if ($start != null){
            $qb->andWhere('( s.end >= :dateStart)')
                ->setParameter('dateStart' , $start);
        }
        if ($end != null){
            $qb->andWhere('( s.start <= :dateEnd)')
                ->setParameter('dateEnd' , $end);
        }
        if ($specialty != null){
            $qb->andWhere('(spec.id = :specialty)')
                ->setParameter('specialty' , $specialty);
        }

        $qb->andWhere("(s.title LIKE '%$text%' OR s.body LIKE '%$text%' OR s.adrs LIKE '%$text%')");


        $qb->orderBy('s.start', 'ASC');
        $qb->groupBy('s.id');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function findImmediate(){
        $now = new \DateTime();
        $now = $now->format('Y-m-d').' 23:59:59';
        $qb = $this->createQueryBuilder('e');
        $qb->select('e');
        $qb->where('e.enabled = true');
        $qb->andWhere('e.start >= :now');
        $qb->orderBy('e.start', 'ASC');
        $qb->setMaxResults(5);
        $qb->setParameter(':now',$now);
        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
