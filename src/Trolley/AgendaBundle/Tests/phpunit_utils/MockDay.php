<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 03.05.16
 * Time: 08:03
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\phpunit_utils;


use Trolley\AgendaBundle\Entity\Day;

class MockDay
{
    use auto_increment;

    /**
     * @param Day    $day
     * @param string $datestring
     */
    public static function Day($day, $datestring)
    {
        $date1 = new \DateTime($datestring);

        $day->getTaDay()->shouldBeCalled()->willReturn($date1);
        $day->getId()->shouldBeCalled()->willReturn(self::getAutoIncrement());
    }
}