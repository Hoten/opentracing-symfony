<?php

namespace Hoten\OpenTracingBundle\Transport;

use DDTrace\Encoder;
use DDTrace\Transport;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TcpTransport implements Transport
{
    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $config;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Encoder $encoder, LoggerInterface $logger = null, array $config = [])
    {
        $this->encoder = $encoder;
        $this->logger = $logger ?: new NullLogger();
        $this->config = $config;
    }

    /**
     * @param Span[][] $traces
     * @return ResponseInterface
     */
    public function send(array $traces)
    {
        $host = $this->config['host'];
        $port = $this->config['port'];
        $data = $this->encoder->encodeTraces($traces);

        $fp = fsockopen($host, $port, $errno, $errstr, 5);
        if (!$fp) {
            $this->logger->debug(sprintf('%s (%d)', $errstr, $errno));
            echo("$errstr ($errno)<br />\n");
        } else {
            $this->fwrite_stream($fp, $data);
            while (!feof($fp)) {
                echo fgets($fp, 128);
            }
            fclose($fp);
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setHeader($key, $value)
    {

    }

    private function fwrite_stream($fp, $string) {
        for ($written = 0; $written < strlen($string); $written += $fwrite) {
            $fwrite = fwrite($fp, substr($string, $written));
            if ($fwrite === false) {
                return $written;
            }
        }
        return $written;
    }
}
