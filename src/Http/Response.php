<?php
namespace Olifant\Http;

use Olifant\Http\Response\FileResponse;
use Olifant\Http\Response\JsonResponse;
use Olifant\Http\Response\AuthAskResponse;
use Olifant\Http\Response\RedirectResponse;
use Olifant\Http\Response\StreamedResponse;
use Olifant\Http\Response\ClientErrorResponse;
use Olifant\Http\Response\ServerErrorResponse;
use Zend\Diactoros\Response as ZendResponse;

class Response extends ZendResponse
{
    use CookieResponseTrait;

    public function __construct($status = 200, array $headers = [])
    {
        parent::__construct('php://memory', $status, $headers);
    }

    public function toTextResponse()
    {
        return (new TextResponse)
            ->withStatus($this->getStatusCode())
            ->withHeaders($this->getAllHeaders());
    }

    public function toJsonResponse()
    {
        return (new JsonResponse)
            ->withStatus($this->getStatusCode())
            ->withHeaders($this->getAllHeaders());
    }

    public function toRedirectResponse()
    {
        return (new RedirectResponse)
            ->withHeaders(
                $this->getAllHeaders()
            );
    }

    public function toFileResponse()
    {
        return (new FileResponse)
            ->withStatus($this->getStatusCode())
            ->withHeaders($this->getAllHeaders());
    }

    public function toStreamedResponse()
    {
        return (new StreamedResponse)
            ->withStatus($this->getStatusCode())
            ->withHeaders($this->getAllHeaders());
    }

    public function toAuthAskResponse()
    {
        return (new AuthAskResponse())->withHeaders(
            $this->getAllHeaders()
        );
    }

    public function toClientErrorResponse()
    {
        return (new ClientErrorResponse)->withHeaders(
            $this->getAllHeaders()
        );
    }

    public function toServerErrorResponse()
    {
        return (new ServerErrorResponse)->withHeaders(
            $this->getAllHeaders()
        );
    }
}