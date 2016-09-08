<?php

namespace Trolley\MailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class notifyAdminController
{

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * notifyAdminController constructor.
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function newCandidacyAction()
    {
        return true;
    }

}
