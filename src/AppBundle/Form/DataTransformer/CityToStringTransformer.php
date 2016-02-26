<?php

namespace AppBundle\Form\DataTransformer;

use
    Symfony\Component\Form\DataTransformerInterface,
    Symfony\Component\Form\Exception\TransformationFailedException,
    Doctrine\Common\Persistence\ObjectManager,
    AppBundle\Entity\City;

class CityToStringTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object to a string (id).
     *
     * @param  City|null $city
     * @return string
     */
    public function transform($city)
    {
        if (null === $city) {
            return '';
        }

        $title = $city->getTitle();

        if ($country = $city->getCountry()) {
            $title .= ', ' . $country->getTitle();
        }

        return $title;
    }

    /**
     * Transforms a string (id) to an object (city).
     *
     * @param  string $id
     *
     * @return City|null
     *
     * @throws TransformationFailedException if object (city) is not found.
     */
    public function reverseTransform($string)
    {
        if (empty($string)) {
            return null;
        }

        $titles       = explode(', ', $string);
        $cityTitle    = $titles[0];
        $countryTitle = count($titles) > 1 ? $titles[1] : null;

        $builder = $this->om->createQueryBuilder();
        $builder
            ->select('city')
            ->from('AppBundle:City', 'city')
            ->leftJoin('AppBundle:Country', 'country', 'WITH', 'country = city.country')
            ->where('city.title = :cityTitle')
            ->orderBy('country.id', 'ASC')
            ->setParameter('cityTitle', $cityTitle)
            ->setMaxResults(1);

        if ($countryTitle) {
            $builder->andWhere('country.title LIKE :countryTitle')
                ->setParameter('countryTitle', '%'.$countryTitle.'%');
        }

        $city = $builder->getQuery()->getOneOrNullResult();

        if (empty($city)) {
            throw new TransformationFailedException(sprintf(
                'Город "%s" не найден!',
                $string
            ));
        }

        return $city;
    }
}