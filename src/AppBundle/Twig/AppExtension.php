<?php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('anons', array($this, 'anonsFilter')),
            new \Twig_SimpleFilter('month', array($this, 'monthFilter')),
        );
    }

    public function anonsFilter($text)
    {
        mb_internal_encoding("UTF-8");
        $text = strip_tags($text);
        $text = mb_substr($text,0,200);
        return $text.'...';
    }

    public function monthFilter($month)
    {
        switch ($month){
            case 1 : return 'Января';
            case 2 : return 'Февраля';
            case 3 : return 'Марта';
            case 4 : return 'Апреля';
            case 5 : return 'Мая';
            case 6 : return 'Июня';
            case 7 : return 'Июля';
            case 8 : return 'Августа';
            case 9 : return 'Сентября';
            case 10 : return 'Октября';
            case 11 : return 'Ноября';
            case 12 : return 'Декабря';
            default: return 'none';
        }
    }

    public function getName()
    {
        return 'app_extension';
    }
}