<?php

namespace Hoten\OpenTracingBundle\Controllers;

interface TraceableController
{
    public function operationName($action = null);
}
