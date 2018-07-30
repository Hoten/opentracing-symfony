<?php

namespace Hoten\OpenTracingBundle\DependencyInjection;

use DDTrace\Encoders\Json as JsonEncoder;
use DDTrace\Encoders\Noop as NoopEncoder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class OpenTracingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('opentracing.enabled', $config['enabled']);
        $container->setParameter('opentracing.sample', $config['sample']);
        $container->setParameter('opentracing.format', $config['format']);
        $container->setParameter('opentracing.encoder', $this->resolveEncoder($config['encoder']));
        $container->setAlias('opentracing.transport', $this->resolveTransport($config['transport']));
        $container->setParameter('opentracing.endpoint', $config['endpoint']);
    }

    private function resolveEncoder($encoderFormat)
    {
        switch ($encoderFormat) {
            case 'noop':
                return NoopEncoder::class;
                break;
            case 'json':
            case '':
                return JsonEncoder::class;
                break;
            default:
                return $encoderFormat;
        }
    }

    private function resolveTransport($transport)
    {

        switch ($transport) {
            case 'noop':
                return 'opentracing.transport.noop';
                break;
            case 'http':
            case '':
                return 'opentracing.transport.http';
                break;
            default:
                return $transport;
        }
    }
}
