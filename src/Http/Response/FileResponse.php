<?php
namespace Olifant\Http\Response;

use finfo;
use Olifant\Http\Response;
use Zend\Diactoros\Stream;
use InvalidArgumentException;
use Zend\Diactoros\CallbackStream;

class FileResponse extends Response
{
    public function __construct($status = 200, array $headers = [])
    {
        parent::__construct($status, $headers);
    }

    private function buildHeaders($response)
    {
        $response = $response
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Connection', 'Keep-Alive')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public');

        return $response;
    }

    public function withContents($contents)
    {
        $new = clone $this;
        $new = $this->buildHeaders($new)
            ->withHeader('Content-Length', (string) strlen($contents))
            ->withHeader('Content-Disposition', 'attachment; filename="Untitled"')
            ->withBody(
                new Stream('data://text/plain,' . $contents)
            );

        return $new;
    }

    public function withFile($path)
    {
        if (!is_readable($path) or !is_file($path)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot access to \'%s\', file does not exist or access denied',
                    $path
                )
            );
        }

        $finfo = new finfo(FILEINFO_MIME);

        $new = clone $this;
        $new = $this->buildHeaders($new)
            ->withHeader('Content-Type', $finfo->file($path))
            ->withHeader('Content-Length', (string) filesize($path))
            ->withHeader('Content-Disposition', 'attachment; filename="' . basename($path) . '"')
            ->withBody(
                new CallbackStream(function () use ($path) {
                    while(@ob_end_flush());
                    readfile($path);
                })
            );

        return $new;
    }

    public function withName($name)
    {
        $new = clone $this;
        $new = $new
            ->withoutHeader('Content-Disposition')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $name .'"');

        return $new;
    }
}