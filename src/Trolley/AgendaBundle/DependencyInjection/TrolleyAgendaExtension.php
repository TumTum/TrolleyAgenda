<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 15:31
 * Copyright: 2014 Tobias Matthaiou
 */

namespace Trolley\AgendaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class TrolleyAgendaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['calendar'])) {
            $name = $this->getAlias() . ".calendar";
            foreach ($config['calendar'] as $key => $value) {
                $container->setParameter("$name.$key", $value);
            }
        }
    }


}