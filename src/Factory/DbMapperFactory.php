<?php
/**
 * @see https://github.com/dotkernel/dot-mapper/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-mapper/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Mapper\Factory;

use Dot\Mapper\Mapper\MapperManager;
use Psr\Container\ContainerInterface;
use Laminas\Hydrator\HydratorPluginManager;

/**
 * Class DbMapperFactory
 * @package Dot\Mapper\Factory
 */
class DbMapperFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $options ?? [];
        if (isset($options['adapter']) && is_string($options['adapter'])) {
            $options['adapter'] = $container->get($options['adapter']);
        }

        if (isset($options['slave_adapter']) && is_string($options['slave_adapter'])) {
            $options['slave_adapter'] = $container->get($options['slave_adapter']);
        }

        if (isset($options['hydrator_manager']) && is_string($options['hydrator_manager'])) {
            $options['hydrator_manager'] = $container->get($options['hydrator_manager']);
        } else {
            $options['hydrator_manager'] = $container->has('HydratorManager')
                ? $container->get('HydratorManager')
                : new HydratorPluginManager($container, []);
        }

        $mapperManager = $container->get(MapperManager::class);

        return new $requestedName($mapperManager, $options);
    }
}
