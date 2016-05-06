<?php
/**
 * Created for TrolleyAgenda
 *
 * @author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * @copyright: 2016 Tobias Matthaiou
 */

namespace Tests\Trolley\AgendaBundle\Util;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Trolley\AgendaBundle\Entity\Day;
use Trolley\AgendaBundle\Entity\User;
use Trolley\AgendaBundle\Repository\DayRepository;
use Trolley\AgendaBundle\Repository\UserRepository;

/**
 * @mixin \Trolley\AgendaBundle\Util\BulksUsersToDays
 */
class BulksUsersToDaysSpec extends ObjectBehavior
{
    public function let(
        EntityManagerInterface $entityManager
    ) {
        $this->beConstructedWith($entityManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Trolley\AgendaBundle\Util\BulksUsersToDays');
    }

    public function it_be_add_tow_user_to_onw_day(
        EntityManagerInterface $entityManager,
        DayRepository $dayRepository,
        UserRepository $userRepository,
        Day $day,
        User $user
    ) {
        $entityManager->getRepository(Argument::is("TrolleyAgendaBundle:Day"))->willReturn($dayRepository)->shouldBeCalled();
        $entityManager->getRepository(Argument::is("TrolleyAgendaBundle:User"))->willReturn($userRepository)->shouldBeCalled();


        $dayRepository->find('31')->willReturn($day)->shouldBeCalled();
        $userRepository->find('20')->willReturn($user)->shouldBeCalled();
        $userRepository->find('errorID')->shouldNotBeCalled();

        $day->addUser(Argument::any())->shouldBeCalled();

        $formular = [
            'dayid_31' => [
                20,
                'errorID'
            ]
        ];

        $this->processForm($formular);
    }



}
