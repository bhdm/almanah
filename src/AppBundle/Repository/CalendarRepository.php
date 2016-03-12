<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CalendarRepository extends EntityRepository
{
    public function activeDaysByMonth($month)
    {
        if ($month < 10) {
            $month = '0' . $month;
        }

        $pdo  = $this->_em->getConnection();
        $stmt = $pdo->prepare("SELECT datetime, gone, id FROM calendar WHERE datetime LIKE '2014-$month%' OR datetime LIKE '2015-$month%' OR datetime LIKE '2016-$month%' OR datetime LIKE '2017-$month%' OR gone LIKE '%.$month.%'");
        $stmt->execute();

        $dates = $stmt->fetchAll();
        $days  = array();

        foreach ($dates as $date) {
            if (!empty($date['gone'])) {
                $gone   = substr($date['gone'], 0, strpos($date['gone'], '.'));
                $days[] = intval($gone);
            }
            else {
                $dt     = new \DateTime($date['datetime']);
                $days[] = intval($dt->format('d'));
            }
        }

        return array_unique($days);
    }

    public function byDate($dateFormat)
    {
        $dateFormat = $this->dateFormat($dateFormat);
        $dateFormat2 = $this->dateFormat($dateFormat);

        $calendars = $this->_em->createQuery('
			SELECT t.id as type, c.id, c.firstName, c.lastName, c.surName, c.title, c.anons, c.text,
				c.date, c.birthdate, c.gone, c.photo, c.dayNumber, c.dayOfWeek, c.month
			FROM EvrikaMainBundle:Calendar c
			JOIN c.type t
			WHERE c.enabled = 1 AND t.id != 4 AND (c.date LIKE :dateFormat
				OR c.birthdate LIKE :dateFormat
				OR c.gone LIKE :dateFormat)
		')->setParameter('dateFormat', $dateFormat . '%')
            ->getResult();

        if (strlen($dateFormat) < 9) {
            $dateFormat = $dateFormat . '.' . date('Y');
        }

        $date = new \DateTime($dateFormat);

        $liquids = $this->_em->createQuery('
			SELECT t.id as type, c.id, c.firstName, c.lastName, c.surName, c.title, c.anons, c.text,
				c.date, c.birthdate, c.gone, c.photo, c.dayNumber, c.dayOfWeek, c.month
			FROM EvrikaMainBundle:Calendar c
			JOIN c.type t WITH t.id = 4
			WHERE c.month = :month
		')->setParameter('month', $date->format('M'))
            ->getResult();

        foreach ($liquids as $c) {
            $dateStr   = "{$c['dayNumber']} {$c['dayOfWeek']} of {$c['month']} " . $date->format('Y');
            $int       = strtotime($dateStr);
            $liquidDay = date('d', $int);

            if ($liquidDay == $date->format('d')) {
                $calendars[] = $c;
            }
        }
        $holidays = array();
        $births   = array();
        $deaths   = array();
        $events   = array();

        foreach ($calendars as $c) {
            switch ($c['type']) {
                case 2:
                    strpos($c['birthdate'], $dateFormat2) === false ? $deaths[] = $c : $births[] = $c;
                    break;
                case 3:
                    $events[] = $c;
                    break;
                default:
                    $holidays[] = $c;
            }
        }

        return array(
            'holidays' => $holidays,
            'births'   => $births,
            'deaths'   => $deaths,
            'events'   => $events,
        );
    }

    /** Корректирует дату, добавляя нули */
    private function dateFormat($dateFormat)
    {
        $parts = explode('.', $dateFormat);
        $day   = intval($parts[0]);
        $month = intval($parts[1]);

        if ($day < 10) {
            $day = '0' . $day;
        }

        if ($month < 10) {
            $month = '0' . $month;
        }

        return $day . '.' . $month;
    }

    /** Находим праздники на конкретный день */
    public function findByDate($date)
    {
        $dateFormat = $date->format('d.m');

        # находим праздники, исторические события и по дате рождения/смерти
        $calendars = $this->_em->createQuery('
			SELECT c
			FROM EvrikaMainBundle:Calendar c
			JOIN c.type t
			WHERE (c.date LIKE :dateFormat AND c.month IS NULL)
				OR c.birthdate LIKE :dateFormat
				OR c.gone LIKE :dateFormat
		')->setParameter('dateFormat', $dateFormat . '%')
            ->getResult();

        # находим плавающие праздники
        $liquids = $this->_em->createQuery('
			SELECT c
			FROM EvrikaMainBundle:Calendar c
			JOIN c.type t
			WHERE c.month = :month
		')->setParameter('month', $date->format('M'))
            ->getResult();

        foreach ($liquids as $calendar) {
            $dateStr   = "{$calendar->getDayNumber()} {$calendar->getDayOfWeek()} of {$calendar->getMonth()} " . $date->format('Y');
            $int       = strtotime($dateStr);
            $liquidDay = date('d', $int);

            if ($liquidDay == $date->format('d')) {
                $calendars[] = $calendar;
            }
        }

        # надо сгруппировать календари по группам
        $grouped = array(
            'holidays' => array(),
            'births'   => array(),
            'deaths'   => array(),
            'events'   => array(),
        );

        foreach ($calendars as $calendar) {
            switch ($calendar->getType()->getId()) {
                case 2:
                    strpos($calendar->getBirthdate(), $dateFormat) !== false
                        ? $grouped['births'][] = $calendar
                        : $grouped['deaths'][] = $calendar;
                    break;
                case 3:
                    $grouped['events'][] = $calendar;
                    break;
                default:
                    $grouped['holidays'][] = $calendar;
            }
        }

        if (empty($grouped['holidays']) && empty($grouped['events']) && empty($grouped['births']) && empty($grouped['deaths'])) {
            return array();
        }

        return $grouped;
    }

    public function findActiveDays($date)
    {
        $month = $date->format('m');
        $days  = array();

        $calendars = $this->_em->createQuery("
			SELECT c
			FROM EvrikaMainBundle:Calendar c
			WHERE (c.date LIKE '%.$month%' AND c.month IS NULL)
				OR c.birthdate LIKE '%.$month.%'
				OR c.gone LIKE '%.$month.%'
				OR c.month = :monthName
		")->setParameter('monthName', $date->format('M'))
            ->getResult();

        foreach ($calendars as $c) {
            if ($birthdate = $c->getBirthdate()) {
                $datetime = strpos($birthdate, ".$month.") !== false ? $birthdate : $c->getGone();
                $datetime = new \DateTime($datetime);
                $days[]   = intval($datetime->format('d'));
            }
            elseif ($c->getMonth()) {
                $int    = strtotime("{$c->getDayNumber()} {$c->getDayOfWeek()} of {$c->getMonth()} " . $date->format('Y'));
                $days[] = intval(date('d', $int));
            }
            else {
                $days[] = intval($c->getDatetime()->format('d'));
            }
        }

        return array_unique($days);
    }
}