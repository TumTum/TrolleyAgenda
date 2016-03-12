<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 17.02.16
 * Time: 19:18
 * Copyright: 2014 Tobias Matthaiou
 */

namespace Trolly\AgendaBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Translation\Translator;

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
        $menu->addChild($tr->trans('menu.userlist'), array('route' => 'trolly_agenda_users_list') );
        $menu->addChild($tr->trans('menu.userlist'), array('route' => 'trolly_agenda_users_list') );
        $menu->addChild($tr->trans('layout.logout', [], 'FOSUserBundle'), array('route' => 'fos_user_security_logout') );

        return $menu;
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

}