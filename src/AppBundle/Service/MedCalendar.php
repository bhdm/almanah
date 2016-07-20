<?php
namespace AppBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class MedCalendar
{
    private $em;

    protected $year;

    protected $month;

    protected $day = null;

    protected $days = array();

    protected $selectedDays = array();

    protected $monthNames = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');

    protected $monthNamed = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');

    protected $date;

    protected $calendars = array();

    protected $today;

    public function __construct(Doctrine $em)
    {
        $this->em    = $em;
        $this->today = new \DateTime('now');
    }

    public function init($dateFormat = 'now')
    {
        # если указан лишь день.месяц или месяц.год
        if (substr_count($dateFormat, '.') == 1) {
            $dateFormat = strlen($dateFormat) < 6 ? $dateFormat . '.' . date('Y') : '1.' . $dateFormat;
        }

        $date        = new \DateTime($dateFormat);
        $this->date  = $date;
        $this->year  = $date->format('Y');
        $this->month = $date->format('m');
        $this->day   = $date->format('d');
        $firstDay    = clone $date;

        $firstDay->modify('first day of this month');
        $dayOfWeek = $firstDay->format('w') - 1;

        if ($dayOfWeek < 0) {
            $dayOfWeek = 6;
        }

        $maxDay             = $dayOfWeek + cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $this->selectedDays = $this->em->getRepository('AppBundle:Calendar')->findActiveDays($this->date);
        $isThisMonth        = $this->today->format('m.Y') == $this->date->format('m.Y');
        $todayDay           = intval($this->today->format('d'));

        # формируем массив дней для рендеринга
        for ($i = 1; $i <= 42; $i++) {
            if ($i > $dayOfWeek && $i <= $maxDay) {
                $day  = $i - $dayOfWeek;
                $data = array('number' => $day, 'classes' => array());

                if (in_array($day, $this->selectedDays)) {
                    $data['classes'][] = 'selected';
                }
                if ($isThisMonth && $day == $todayDay) {
                    $data['classes'][] = 'today';
                }
                if ($day == $this->day) {
                    $data['classes'][] = 'chosen';
                }

                $this->days[] = $data;
            }
            else {
                $this->days[] = null;
            }
        }

        if ($this->day !== null) {
            $this->calendars = $this->em->getRepository('AppBundle:Calendar')->findbyDate($this->date);
        }
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return array
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param array $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return array
     */
    public function getSelectedDays()
    {
        return $this->selectedDays;
    }

    /**
     * @param array $selectedDays
     */
    public function setSelectedDays($selectedDays)
    {
        $this->selectedDays = $selectedDays;
    }

    public function getTitle()
    {
        return $this->day === null
            ? $this->monthNames[$this->month - 1] . ' ' . $this->year
            : intval($this->day) . ' ' . $this->monthNamed[$this->month - 1] . ' ' . $this->year;
    }

    public function getDayMonth()
    {
        return $this->day . ' ' . $this->monthNamed[$this->month - 1] . ' ' . $this->year;
    }

    public function formatByDay($day)
    {
        return $day . '.' . $this->month . '.' . $this->year;
    }

    public function checkMatchToday($day)
    {
        return $this->date->format('m.Y') == $this->today->format('m.Y') && intval($day) == intval($this->today->format('d'));
    }

    public function getNext()
    {
        $next = clone $this->date;
        $next->modify('+ 1 month');

        return $next->format('m.Y');
    }

    public function getPrev()
    {
        $prev = clone $this->date;
        $prev->modify('- 1 month');

        return $prev->format('m.Y');
    }

    /**
     * @return array
     */
    public function getCalendars()
    {
        return $this->calendars;
    }

    /**
     * @param array $calendars
     */
    public function setCalendars($calendars)
    {
        $this->calendars = $calendars;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function getMonthNames()
    {
        return $this->monthNames;
    }

    /**
     * @param array $monthNames
     */
    public function setMonthNames($monthNames)
    {
        $this->monthNames = $monthNames;
    }
}