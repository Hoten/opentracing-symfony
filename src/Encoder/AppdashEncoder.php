<?php

namespace Hoten\OpenTracingBundle\Encoder;

use DDTrace\Encoder;
use DDTrace\Transport;
use Hoten\OpenTracingBundle\Proto\CollectPacket;
use \Hoten\OpenTracingBundle\Proto\CollectPacket_SpanID;

class AppdashEncoder implements Encoder
{
    /**
     * @param Span[][]|array $traces
     * @return string
     */
    public function encodeTraces(array $traces)
    {
        $payload = '';

        foreach ($traces as $trace) {
            foreach ($trace as $span) {
                $packet = new CollectPacket();
                
                $protoSpan = new CollectPacket_SpanID();
                $protoSpan->setTrace(hexdec($span->getTraceId()));
                $protoSpan->setSpan(hexdec($span->getSpanId()));
                $protoSpan->setParent(hexdec($span->getParentId()));
                $packet->setSpan($protoSpan);

                $packet->setAnnotation([]);

                $payload = $payload . $packet->serializeToString();
            }
        }

        return $payload;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        throw new \Error('TCPTransport::getContentType n/a');
    }
}