<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 03.04.16
 * Time: 15:41
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\phpunit_utils;


trait autoClearEntity
{
    /**
     * @var array
     */
    protected $entity = [];

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        self::bootKernel();
        parent::setUp();
    }

    protected function clearEntity($entity)
    {
        $this->entity[] = $entity;

        return $entity;
    }

    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        $manager = $this->getDoctrine()->getManager();
        foreach ($this->entity as $day) {
            $manager->remove($day);
        }
        $manager->flush();
        $this->entity = [];
        parent::tearDown();
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return self::$kernel->getContainer()->get('doctrine');
    }
}