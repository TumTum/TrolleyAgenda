<?php

namespace Trolley\MailBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Trolley\MailBundle\Controller\notifyAdminController;

class notifyAdminControllerTest extends KernelTestCase
{

    public function testNewcandidacy()
    {
        static::bootKernel();
        $kernel = static::$kernel;

        /** @var notifyAdminController $notifyAdmin */
        $notifyAdmin = $kernel->getContainer()->get('trolley.mail.notifyadmin');

        $this->assertTrue($notifyAdmin->newCandidacyAction());
    }

}
