<?php
namespace Olifant\Http\Response;

use Closure;
use Olifant\Http\Response;
use Zend\Diactoros\CallbackStream;

class StreamedResponse extends Response
{
    /**
     * @param integer $status  code
     * @param array   $headers list
     */
    public function __construct($status = 200, array $headers = [])
    {
        parent::__construct('php://memory', $status, $headers);
    }

    /**
     * Set stream callback
     *
     * @param  Closure $callback instance
     *
     * @return self
     */
    public function withCallback(Closure $callback)
    {
        $new = clone $this;

        return $new->withBody(new CallbackStream($callback));
    }
}