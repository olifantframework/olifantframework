<?php
namespace Olifant\Http\Response;

use Olifant\Http\Response;

class ClientErrorResponse extends Response
{
	public function __construct($status = 400, array $headers = [])
	{
		parent::__construct('php://memory', $status, $headers);
	}
}