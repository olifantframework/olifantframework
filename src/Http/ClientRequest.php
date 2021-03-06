<?php
namespace Olifant\Http;

use Zend\Diactoros\Request as ZendRequest;

class ClientRequest extends ZendRequest
{
    use CookieRequestTrait;

    private $json;
    private $files = [];
    private $requestParams = [];

    public function __construct($uri = null, $method = 'GET', array $headers = [])
    {
        parent::__construct($uri, $method, $body = 'php://temp', $headers);
    }

    public function withRequestParams(array $params)
    {
        $new = clone $this;
        $new->setRequestParams($params);

        return $new;
    }

    private function setRequestParams(array $params)
    {
        $this->requestParams += $params;
    }

    public function getRequestParams()
    {
        return $this->requestParams;
    }

    public function withFile($name, $path)
    {
        $new = clone $this;
        $new->addFile($name, $path);

        return $new;
    }

    public function getFiles()
    {
        return $this->files;
    }

    private function addFile($name, $path)
    {
        $this->files[] = [
            'name' => $name,
            'path' => $path
        ];
    }

    public function withJson($json)
    {
        $new = clone $this;
        $new->setJson($json);

        return $new;
    }

    public function setJson($json)
    {
        $this->json = $json;
    }

    public function getJson()
    {
        return $this->json;
    }
}