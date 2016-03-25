<?php
/**
 * Created for TrollyAgenda
 * Author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * Date: 25.03.16
 * Time: 15:39
 * Copyright: 2014 Tobias Matthaiou
 */


namespace Trolley\AgendaBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('trolley_agenda');

        $weekday = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $rootNode
            ->children()
                ->arrayNode('calendar')
                    ->children()
                        ->arrayNode('every_day')
                            ->info('On the days where the trolley goes out.')
                            ->defaultValue($weekday)
                            ->isRequired()
                            ->prototype('scalar')
                                ->validate()
                                ->ifNotInArray($weekday)
                                   ->thenInvalid('Please choose one of '. json_encode($weekday))
                                ->end()
                            ->end()
                        ->end()
                        ->integerNode('month_looking_ahead')
                            ->info('Shows expected the next three months')
                            ->defaultValue(3)
                        ->end()
                        ->integerNode('month_retrospective')
                            ->info('Displays the last months for Admin')
                            ->defaultValue(1)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;

    }


}