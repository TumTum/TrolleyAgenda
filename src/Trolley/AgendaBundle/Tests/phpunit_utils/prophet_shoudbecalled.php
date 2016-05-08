<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 06.05.16
 * Time: 12:03
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\phpunit_utils;


trait prophet_shoudbecalled
{

    /**
     * Makiert alle Funktion zum Aufruf
     *
     * @param array $liste
     */
    protected static function markShouldBeCalled($liste, $shouldBeCalled)
    {
        if($shouldBeCalled) {
            foreach ($liste as $func) {
                $func->shouldBeCalled();
            }
        }
    }
}