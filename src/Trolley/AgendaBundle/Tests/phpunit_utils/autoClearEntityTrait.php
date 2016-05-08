<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 03.04.16
 * Time: 15:41
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\phpunit_utils;


trait autoClearEntityTrait
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
        global $kernel;
        $kernel = self::$kernel;

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
        try {
            $this->clearTable([
                'day',
                'fos_user',
                'history_service',
            ]);
        } catch (\Exception $e){}
        parent::tearDown();
    }

    /**
     * @param array $tables
     */
    protected function clearTable(array $tables)
    {
        $DBALConnection = $this->getDBALConnection();
        foreach ($tables as $table) {
            $query = $DBALConnection->createQueryBuilder('d')->delete($table)->where('1');
            $DBALConnection->executeQuery($query);
        }
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return self::$kernel->getContainer()->get('doctrine');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDBALConnection()
    {
        return self::$kernel->getContainer()->get('database_connection');
    }
}