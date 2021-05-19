<?php

namespace ContainerNpuCWD4;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getNelmioApiDoc_Describers_ConfigService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'nelmio_api_doc.describers.config' shared service.
     *
     * @return \Nelmio\ApiDocBundle\Describer\ExternalDocDescriber
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/nelmio/api-doc-bundle/Describer/DescriberInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/nelmio/api-doc-bundle/Describer/ExternalDocDescriber.php';

        return $container->privates['nelmio_api_doc.describers.config'] = new \Nelmio\ApiDocBundle\Describer\ExternalDocDescriber(['host' => 'billing.study-on.local:82', 'schemes' => [0 => 'http', 1 => 'https'], 'info' => ['title' => 'My App', 'description' => 'This is an awesome app!', 'version' => '1.0.0']]);
    }
}
