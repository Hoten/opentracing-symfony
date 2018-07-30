<?php

namespace Hoten\OpenTracingBundle\Listeners;

use Hoten\OpenTracingBundle\Events\TracingTerminated;
use OpenTracing\Tracer;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

final class TracingTerminator
{
    private $tracer;

    public function __construct(
        Tracer $tracer,
        MessageBus $messageBus
    ) {
        $this->tracer = $tracer;
        $this->messageBus = $messageBus;
    }

    public function onKernelTerminate(PostResponseEvent $event)
    {
        $this->messageBus->handle(TracingTerminated::create($this->tracer));
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->messageBus->handle(TracingTerminated::create($this->tracer));
    }
}
