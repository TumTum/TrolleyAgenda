<?php
/**
 * Created for TrolleyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 17.02.16
 * Time: 19:18
 * Copyright: 2014 Tobias Matthaiou
 */

namespace Trolley\AgendaBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Translation\Translator;
use Trolley\AgendaBundle\Entity\User;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        global $kernel;
        /** @var Translator $tr */
        $tr = $kernel->getContainer()->get('translator');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        if (!$this->isGranted('ROLE_USER')) {
            $menu->addChild($tr->trans('layout.login', [], 'FOSUserBundle'), array('route' => 'fos_user_security_login') );
            return $menu;
        }

        $menu->addChild($tr->trans('menu.home'), array('route' => 'startpage'));
        $menu->addChild($tr->trans('menu.userlist'), array('route' => 'trolley_agenda_users_list') );
        $menu->addChild($tr->trans('menu.userlist'), array('route' => 'trolley_agenda_users_list') );

        $menuProfile = $this->addDropdownMenu($tr->trans('menu.user') . " <small>(".$this->getUsername().")</small>", $menu);
        $menuProfile->setLabel($menuProfile->getLabel());
        $menuProfile->addChild($tr->trans('change_password.submit',[], 'FOSUserBundle'), array('route' => 'fos_user_change_password'));
        $this->addSeppartor($menuProfile);
        $menuProfile->addChild($tr->trans('layout.logout', [], 'FOSUserBundle'), array('route' => 'fos_user_security_logout') );

        return $menu;
    }

    /**
     * Erstellt ein dropdown Menu
     *
     * @param FactoryInterface $factory
     *
     * @return ItemInterface
     */
    public function addDropdownMenu($name, ItemInterface $menu)
    {
        return $menu->addChild($name)
            ->setExtra('safe_label', true)
            ->setLabel($name . ' <span class="caret"></span>')
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu')
            ->setLinkAttributes([
                'class' => 'dropdown-toggle',
                'data-toggle' => "dropdown",
                "role" => "button",
                "aria-haspopup" => "true",
                "aria-expanded" => "false"
            ])
            ->setUri('#');
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    public function addSeppartor(ItemInterface $menu)
    {
        return $menu->addChild('')
            ->setAttributes([
                'role'  => 'separator',
                'class' => 'divider'
            ])
            ;
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @param mixed $attributes The attributes
     * @param mixed $object     The object
     *
     * @return bool
     *
     * @throws \LogicException
     */
    protected function isGranted($attributes, $object = null)
    {
        global $kernel;
        $container = $kernel->getContainer();

        if (!$container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        return $container->get('security.authorization_checker')->isGranted($attributes, $object);
    }

    /**
     * den Aktuellen Username
     *
     * @return string
     */
    protected function getUsername()
    {
        global $kernel;
        $container = $kernel->getContainer();
        /** @var User $user */
        $user = $container->get('security.token_storage')->getToken()->getUser();
        if (empty(trim($user->getFirstlastname()))) {
            return $user->getUsername();
        }
        return $user->getFirstlastname();
    }

}