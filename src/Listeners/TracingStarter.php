<?php

namespace Hoten\OpenTracingBundle\Listeners;

use Hoten\OpenTracingBundle\Controllers\TraceableController;
use OpenTracing\Carriers\HttpHeaders;
use OpenTracing\Exceptions\SpanContextNotFound;
use OpenTracing\Formats;
use OpenTracing\Span;
use OpenTracing\Tags;
use OpenTracing\Tracer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class TracingStarter
{
    private $dispatcher;
    private $tracer;
    private $env;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Tracer $tracer,
        $env = ''
    ) {
        $this->dispatcher = $dispatcher;
        $this->tracer = $tracer;
        $this->env = $env;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if ($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST)
        {
            $this->startTracingForMasterRequest($event);
        }
    }

    private function startTracingForMasterRequest(FilterControllerEvent $event)
    {
        $controllerAction = $event->getRequest()->attributes->get('_controller');
        $actionName = substr($controllerAction, strpos($controllerAction, '::') + 1);

        if ($event->getController() instanceof TraceableController) {
            $operationName = $event->getController()->operationName($actionName);
        } else {
            $operationName = $actionName;
        }

        try {
            $spanContext = $this->extractContextFromRequest($event->getRequest());
            $span = $this->tracer->startSpan($operationName, $spanContext ?? []);
        } catch (SpanContextNotFound $e) {
            $span = $this->tracer->startSpan($operationName);
        }

        // is this useful?
        $span->setTag('env', $this->env);

        $this->subscribeToFinishEvent($span);
    }

    private function extractContextFromRequest(Request $request)
    {
        return $this->tracer->extract(
            Formats\TEXT_MAP,
            array_map(
                function($values) {
                    return $values[0];
                },
                $request->headers->all()
            )
        );
    }

    private function subscribeToFinishEvent(Span $span)
    {
        $this->dispatcher->addListener('kernel.terminate', function(PostResponseEvent $event) use ($span) {
            $span->finish();
        }, -100);

        $this->dispatcher->addListener('kernel.exception', function(GetResponseForExceptionEvent $event) use ($span) {
            $span->setTag(Tags\ERROR, $event->getException());
            $span->finish();
        }, -100);
    }
}