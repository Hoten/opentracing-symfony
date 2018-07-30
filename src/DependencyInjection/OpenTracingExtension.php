<?php

namespace Hoten\OpenTracingBundle\DependencyInjection;

use DDTrace\Encoders\Json as JsonEncoder;
use DDTrace\Encoders\Noop as NoopEncoder;
use Hoten\OpenTracingBundle\Encoder\AppdashEncoder;
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

        $container->setParameter('open_tracing.enabled', $config['enabled']);
        $container->setParameter('open_tracing.sample', $config['sample']);
        $container->setParameter('open_tracing.format', $config['format']);
        $container->setParameter('open_tracing.encoder', $this->resolveEncoder($config['encoder']));
        $container->setAlias('open_tracing.transport', $this->resolveTransport($config['transport']));
        $container->setParameter('open_tracing.endpoint', $config['endpoint']);
        $container->setParameter('open_tracing.host', $config['host']);
        $container->setParameter('open_tracing.port', $config['port']);
    }

    private function resolveEncoder($encoderFormat)
    {
        switch ($encoderFormat) {
            case 'noop':
                return NoopEncoder::class;
                break;
            case 'appdash':
                return AppdashEncoder::class;
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
                return 'open_tracing.transport.noop';
                break;
            case 'tcp':
                return 'open_tracing.transport.tcp';
                break;
            case 'http':
            case '':
                return 'open_tracing.transport.http';
                break;
            default:
                return $transport;
        }
    }
}
