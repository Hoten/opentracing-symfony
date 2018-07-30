<?php

namespace Hoten\OpenTracingBundle\Tracers;

use DDTrace\Tracer as DDTracer;
use DDTrace\Transport;
use OpenTracing\Tracer;
use OpenTracing\GlobalTracer;

class TracerFactory
{
    public function build(bool $enabled, float $sample, Transport $transport): Tracer
    {
        if ($enabled && $sample) {
            $rate = (float) $sample;
            $enabled = mt_rand() / mt_getrandmax() < $rate;
        }

        $tracer = new DDTracer($transport, null, ["enabled" => $enabled]);
        GlobalTracer::set($tracer);
        return $tracer;
    }
}
