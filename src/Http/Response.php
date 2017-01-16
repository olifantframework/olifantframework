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

    /**
     * @param integer $status  code
     * @param array   $headers list
     */
    public function __construct($status = 200, array $headers = [])
    {
        parent::__construct('php://memory', $status, $headers);
    }

    public function getAllHeaders()
    {
        $headers = [];
        foreach ($this->getHeaders() as $key => $head) {
            $headers[$key] = $response->getHeaderLine($key);
        }

        return $headers;
    }

    public function withHeaders(array $headers)
    {
        $new = clone $this;
        foreach ($headers as $key => $head) {
            $new = $new->withHeader($key, $head);
        }

        return $new;
    }

    /**
     * Cast to JsonResponse
     *
     * @return JsonResponse
     */
    public function toJsonResponse()
    {
        return (new JsonResponse)
            ->withStatus($this->getStatusCode())
            ->withHeaders($this->getAllHeaders());
    }

    /**
     * Cast to RedirectResponse
     *
     * @return RedirectResponse
     */
    public function toRedirectResponse()
    {
        return (new RedirectResponse)
            ->withHeaders(
                $this->getAllHeaders()
            );
    }

    /**
     * Cast to FileResponse
     *
     * @return FileResponse
     */
    public function toFileResponse()
    {
        return (new FileResponse)
            ->withStatus($this->getStatusCode())
            ->withHeaders($this->getAllHeaders());
    }

    /**
     * Cast to StreamedResponse
     *
     * @return StreamedResponse
     */
    public function toStreamedResponse()
    {
        return (new StreamedResponse)
            ->withStatus($this->getStatusCode())
            ->withHeaders($this->getAllHeaders());
    }

    /**
     * Cast to AuthAskResponse
     *
     * @return AuthAskResponse
     */
    public function toAuthAskResponse()
    {
        return (new AuthAskResponse)->withHeaders(
            $this->getAllHeaders()
        );
    }

    /**
     * Cast to ClientErrorResponse
     *
     * @return ClientErrorResponse
     */
    public function toClientErrorResponse()
    {
        return (new ClientErrorResponse)->withHeaders(
            $this->getAllHeaders()
        );
    }

    /**
     * Cast to ServerErrorResponse
     *
     * @return ServerErrorResponse
     */
    public function toServerErrorResponse()
    {
        return (new ServerErrorResponse)->withHeaders(
            $this->getAllHeaders()
        );
    }
}