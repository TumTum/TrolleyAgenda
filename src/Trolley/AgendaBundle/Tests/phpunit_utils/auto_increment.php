<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 03.05.16
 * Time: 08:01
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\phpunit_utils;


trait auto_increment
{
    private static $counter = 0;

    /**
     * @return int
     */
    private static function getAutoIncrement()
    {
        return self::$counter++;
    }
}