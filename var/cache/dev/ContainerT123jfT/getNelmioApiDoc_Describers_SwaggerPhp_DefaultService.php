<?php

namespace ContainerT123jfT;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getNelmioApiDoc_Describers_SwaggerPhp_DefaultService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'nelmio_api_doc.describers.swagger_php.default' shared service.
     *
     * @return \Nelmio\ApiDocBundle\Describer\SwaggerPhpDescriber
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/nelmio/api-doc-bundle/Describer/ModelRegistryAwareInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/nelmio/api-doc-bundle/Describer/ModelRegistryAwareTrait.php';
        include_once \dirname(__DIR__, 4).'/vendor/nelmio/api-doc-bundle/Describer/SwaggerPhpDescriber.php';
        include_once \dirname(__DIR__, 4).'/vendor/nelmio/api-doc-bundle/Util/ControllerReflector.php';

        return $container->privates['nelmio_api_doc.describers.swagger_php.default'] = new \Nelmio\ApiDocBundle\Describer\SwaggerPhpDescriber(($container->privates['nelmio_api_doc.routes.default'] ?? $container->load('getNelmioApiDoc_Routes_DefaultService')), ($container->privates['nelmio_api_doc.controller_reflector'] ?? ($container->privates['nelmio_api_doc.controller_reflector'] = new \Nelmio\ApiDocBundle\Util\ControllerReflector($container))), ($container->privates['annotations.reader'] ?? $container->load('getAnnotations_ReaderService')), ($container->privates['logger'] ?? ($container->privates['logger'] = new \Symfony\Component\HttpKernel\Log\Logger())));
    }
}
