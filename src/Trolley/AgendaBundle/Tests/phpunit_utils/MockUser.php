<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 03.05.16
 * Time: 07:53
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\Tests\phpunit_utils;


use Trolley\AgendaBundle\Entity\User;

class MockUser
{
    use auto_increment;
    use prophet_shoudbecalled;

    /**
     * @param User   $user
     * @param string $username
     * @param bool   $admin
     *
     * @return mixed
     */
    public static function User($user, $username = 'testuser', $admin = false, $shouldBeCalled = false)
    {
        $user->getId()
            ->willReturn(self::getAutoIncrement());
        $user->getUsername()
            ->willReturn($username);
        $user->getFirstlastname()
            ->willReturn($username." bot");
        $user->getEmail()
            ->willReturn($username.'@localhost');
        $user->isEnabled()
            ->willReturn(true);
        $user->hasRole('ROLE_ADMIN')
            ->willReturn($admin);

        self::markShouldBeCalled([
            $user->getId(),
            $user->getUsername(),
            $user->getFirstlastname(),
            $user->getEmail(),
        ], $shouldBeCalled);

        return $user;
    }
}