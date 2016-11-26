<?php
namespace Olifant\Http;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client as GuzzleClient;

class Client extends GuzzleClient
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    private function processOptions(RequestInterface $request, array $options)
    {
        if ($request instanceof \Olifant\Http\ClientRequest) {
            $files = $request->getFiles();
            $params = $request->getRequestParams();
            $method = $request->getMethod();

            if ($params) {
                if ($method == 'GET') {
                    $options['query'] = $params;
                } else {
                    $options['body'] = http_build_query($params);

                    if ($files) {
                        //$options['multipart'] =
                    }
                }
            }
        }

        return $options;
    }

    public function send(RequestInterface $request, array $options = [])
    {
        $response = parent::send($request, $options);

        return Utils::proxyResponse($response);
    }

    public function sendAsync(RequestInterface $request, array $options = [])
    {
        return parent::sendAsync($request, $options)->then(function($response) {
            return Utils::proxyResponse($response);
        });
    }
}