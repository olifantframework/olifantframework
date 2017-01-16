<?php
namespace Olifant\Http;

use Zend\Diactoros\Stream;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client as GuzzleClient;

class HttpClient extends GuzzleClient
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
            $json = $request->getJson();

            if ($files) {
                $options['multipart'] = array_map(
                    function($file) {
                        if (isset($file['path'])) {
                            $file['contents'] = new Stream($file['path']);
                        }

                        return $file;
                    },
                    $files
                );
            }

            if ($params) {
                if ($method == 'GET') {
                    $options['query'] = $params;
                } else {
                    if ($files) {
                        $options['multipart'] = array_merge(
                            $options['multipart'],
                            $this->buildMultipart($params)
                        );
                    } else {
                        $options['form_params'] = $params;
                    }
                }
            }

            if (null !== $json) {
                $options['json'] = $json;
            }
        }

        return $options;
    }

    private function buildMultipart(array $params)
    {
        $params = http_build_query($params);
        $params = explode('&', $params);

        return array_map(function($item) {
            list($name, $contents) = explode('=', $item, 2);

            return [
                'name' => urldecode($name),
                'contents' => urldecode($contents)
            ];
        }, $params);
    }

    public function send(RequestInterface $request, array $options = [])
    {
        $options = $this->processOptions($request, $options);
        $response = parent::send($request, $options);

        return Utils::proxyResponse($response);
    }

    public function sendAsync(RequestInterface $request, array $options = [])
    {
        $options = $this->processOptions($request, $options);

        return parent::sendAsync($request, $options)->then(function($response) {
            return Utils::proxyResponse($response);
        });
    }
}