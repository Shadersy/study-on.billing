<?php

namespace ContainerNpuCWD4;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getJmsSerializer_MetadataFactoryService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'jms_serializer.metadata_factory' shared service.
     *
     * @return \Metadata\MetadataFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/jms/metadata/src/MetadataFactoryInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/jms/metadata/src/AdvancedMetadataFactoryInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/jms/metadata/src/MetadataFactory.php';
        include_once \dirname(__DIR__, 4).'/vendor/jms/metadata/src/Driver/DriverInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/jms/metadata/src/Driver/LazyLoadingDriver.php';
        include_once \dirname(__DIR__, 4).'/vendor/jms/metadata/src/Cache/CacheInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/jms/metadata/src/Cache/ClearableCacheInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/jms/metadata/src/Cache/FileCache.php';

        $container->privates['jms_serializer.metadata_factory'] = $instance = new \Metadata\MetadataFactory(new \Metadata\Driver\LazyLoadingDriver(new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'metadata_driver' => ['services', 'jms_serializer.metadata_driver', 'getJmsSerializer_MetadataDriverService', true],
        ], [
            'metadata_driver' => '?',
        ]), 'metadata_driver'), 'Metadata\\ClassHierarchyMetadata', true);

        $instance->setCache(($container->privates['jms_serializer.metadata.cache.file_cache'] ?? ($container->privates['jms_serializer.metadata.cache.file_cache'] = new \Metadata\Cache\FileCache(($container->targetDir.''.'/jms_serializer')))));
        $instance->setIncludeInterfaces(false);

        return $instance;
    }
}
