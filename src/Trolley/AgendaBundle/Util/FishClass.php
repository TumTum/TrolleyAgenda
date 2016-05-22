<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 22.05.16
 * Time: 16:46
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Util;

/**
 * Class FishClass
 * Eine Klasse die immer leer zurück gibt egal wie sie auf gerufen wird
 * @package Trolley\AgendaBundle\Util
 */
class FishClass
{
    /**
     * @inheritDoc
     */
    function __call($name, $arguments)
    {
        return "";
    }

}