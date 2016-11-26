<?php
namespace Olifant\Http;

use Psr\Http\Message\ResponseInterface;

class Utils
{
    public static function proxyResponse(ResponseInterface $response)
    {
        if ($response instanceof Olifant\Http\Response) {
            return $response;
        }

        $protocolVersion = $response->getProtocolVersion();
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();
        $stream = $response->getBody();

        $headers = [];
        foreach ($response->getHeaders() as $key => $head) {
            $headers[$key] = $response->getHeaderLine($key);
        }

        $response = new Response;
        $response = $response
            ->withProtocolVersion($protocolVersion)
            ->withStatus($statusCode, $reasonPhrase);

        foreach ($headers as $header => $line) {
            $response = $response->withHeader($header, $line);
        }

        $response = $response->withBody($stream);

        return $response;
    }
}