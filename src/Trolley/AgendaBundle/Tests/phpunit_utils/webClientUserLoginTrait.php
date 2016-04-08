<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 08.04.16
 * Time: 21:00
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\phpunit_utils;


use Trolley\AgendaBundle\Entity\User;

trait webClientUserLoginTrait
{
    /**
     * Erstellt einen Normalen Login User
     *
     * @return User
     */
    protected function createLoginUser()
    {
        $testUser = new User();
        $testUser->setUsername('testlogin');
        $testUser->setPlainPassword('0e487493');
        $testUser->setEmail('test@localhost');
        $testUser->setEnabled(true);

        return $testUser;
    }

    /**
     * Erstellt einen Admin Login User
     *
     * @return User
     */
    protected function createLoginAdminUser()
    {
        $testAdminUser = new User();
        $testAdminUser->setUsername('testadminlogin');
        $testAdminUser->setPlainPassword('0e487493');
        $testAdminUser->setEmail('testadmin@localhost');
        $testAdminUser->addRole('ROLE_ADMIN');
        $testAdminUser->setEnabled(true);

        return $testAdminUser;
    }

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Client $client
     * @param bool                                   $asAdmin
     *
     * @return User
     */
    public function login($client, $asAdmin = false)
    {
        $testUser = $asAdmin ? $this->createLoginAdminUser() : $this->createLoginUser();
        $username = $testUser->getUsername();
        $passwort = $testUser->getPlainPassword();

        $this->saveInDb([$testUser]);

        $crawler = $client->request('GET', '/login');
        $client->followRedirects();

        $loginForm = $crawler->selectButton('_submit')->form();

        $loginForm['_username'] = $username;
        $loginForm['_password'] = $passwort;

        $crawler = $client->submit($loginForm);

        $this->assertFalse((bool)$crawler->filter('.form-signin')->count(), "Can't Login User.");

        return $testUser;

    }

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Client $client
     *
     * @return User
     */
    public function loginAsAdmin($client)
    {
        return $this->login($client, true);
    }

}