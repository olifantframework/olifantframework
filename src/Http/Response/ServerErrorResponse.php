<?php
namespace Olifant\Http\Response;

use Olifant\Http\Response;

class ServerErrorResponse extends Response
{
    /**
     * @param integer $status  code
     * @param array   $headers list
     */
    public function __construct($status = 500, array $headers = [])
    {
        parent::__construct('php://memory', $status, $headers);
    }
}
