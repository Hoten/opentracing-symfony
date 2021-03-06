<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: collector.proto

namespace Hoten\OpenTracingBundle\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Annotation is any number of annotations for the span to be collected.
 *
 * Generated from protobuf message <code>Hoten.OpenTracingBundle.Proto.CollectPacket.Annotation</code>
 */
class CollectPacket_Annotation extends \Google\Protobuf\Internal\Message
{
    /**
     * key is the annotation's key.
     *
     * Generated from protobuf field <code>string key = 1;</code>
     */
    private $key = '';
    /**
     * value is the annotation's value, which may be either human or
     * machine readable, depending on the schema of the event that
     * generated it.
     *
     * Generated from protobuf field <code>bytes value = 2;</code>
     */
    private $value = '';

    public function __construct() {
        \Hoten\OpenTracingBundle\Proto\GPBMetadata\Collector::initOnce();
        parent::__construct();
    }

    /**
     * key is the annotation's key.
     *
     * Generated from protobuf field <code>string key = 1;</code>
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * key is the annotation's key.
     *
     * Generated from protobuf field <code>string key = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setKey($var)
    {
        GPBUtil::checkString($var, True);
        $this->key = $var;

        return $this;
    }

    /**
     * value is the annotation's value, which may be either human or
     * machine readable, depending on the schema of the event that
     * generated it.
     *
     * Generated from protobuf field <code>bytes value = 2;</code>
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * value is the annotation's value, which may be either human or
     * machine readable, depending on the schema of the event that
     * generated it.
     *
     * Generated from protobuf field <code>bytes value = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setValue($var)
    {
        GPBUtil::checkString($var, False);
        $this->value = $var;

        return $this;
    }

}

