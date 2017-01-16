<?php
namespace Olifant\Http\Response;

use Olifant\Http\Response;

class ClientErrorResponse extends Response
{
    /**
     * @param integer $status  code
     * @param array   $headers list
     */
	public function __construct($status = 400, array $headers = [])
	{
		parent::__construct('php://memory', $status, $headers);
	}
}