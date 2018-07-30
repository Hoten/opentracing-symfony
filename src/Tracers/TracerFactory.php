<?php

namespace Hoten\OpenTracingBundle\Tracers;

use DDTrace\Tracer as DDTracer;
use DDTrace\Transport;
use OpenTracing\Tracer;
use OpenTracing\GlobalTracer;
use OpenTracing\NoopTracer;

class TracerFactory
{
    public function build(bool $enabled, float $sample, Transport $transport): Tracer
    {
        if (!$enabled) {
            $tracer = new NoopTracer();
        }

        if ($sample) {
            $rate = (float) $sample;
            if (mt_rand() / mt_getrandmax() < $rate) {
                $tracer = new NoopTracer();
            }
        }

        if (!$tracer) {
            $tracer = new DDTracer($transport);
        }

        GlobalTracer::set($tracer);
        return $tracer;
    }
}
