<?php
namespace Olifant\Http\Response;

use Olifant\Http\Response;

class ServerErrorResponse extends Response
{
    public function __construct($status = 500, array $headers = [])
    {
        parent::__construct('php://memory', $status, $headers);
    }
}
