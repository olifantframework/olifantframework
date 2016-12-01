<?php
namespace Olifant;

use Olifant\Kernel\KernelException;

class FacadeException extends KernelException
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }
}