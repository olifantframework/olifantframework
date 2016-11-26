<?php
namespace Olifant\Http;

use Psr\Http\Message\RequestInterface;

class ClientInfo
{
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function getHTTPReferer()
    {
        if ($this->request->hasHeader('Referer')) {
            return $this->request->getHeaderLine('Referer');
        }

        return false;
    }

    public function getUserAgent()
    {
        return $this->request->getHeaderLine('User-Agent');
    }

    public function getIP()
    {
        return (
            getenv('HTTP_CLIENT_IP') ? :
            getenv('HTTP_X_FORWARDED_FOR') ? :
            getenv('HTTP_X_FORWARDED') ? :
            getenv('HTTP_FORWARDED_FOR') ? :
            getenv('HTTP_FORWARDED') ? :
            getenv('REMOTE_ADDR')
        );
    }

    private function parseHelper($value)
    {
        $list = explode(',', $value);

        return array_map(function ($e) {
            return trim(reset(explode(';', $e)));
        }, $list);
    }

    public function getAcceptableContent()
    {
        if ($this->request->hasHeader('Accept')) {
            return $this->parseHelper(
                $this->request->getHeaderLine('Accept')
            );
        }

        return false;
    }

    public function getBestAccept()
    {
        $accept = $this->getAcceptableContent();

        if (!$accept) return false;

        return reset($accept);
    }

    public function getAcceptableEncodings()
    {
        if ($this->request->hasHeader('Accept-Encoding')) {
            return $this->parseHelper(
                $this->request->getHeaderLine('Accept-Encoding')
            );
        }

        return false;
    }

    public function getClientCharsets()
    {
        if ($this->request->hasHeader('Accept-Charset')) {
            return $this->parseHelper(
                $this->request->getHeaderLine('Accept-Charset')
            );
        }

        return false;
    }

    public function getBestCharset()
    {
        $charset = $this->getClientCharsets();

        if (!$charset) return false;

        return reset($charset);
    }

    public function getLanguages()
    {
        if ($this->request->hasHeader('Accept-Language')) {
            return $this->parseHelper($this->request->getHeaderLine('Accept-Language'));
        }

        return false;
    }

    public function getBestLanguage()
    {
        $langs = $this->getLanguages();

        if (!$langs) return false;

        return reset($langs);
    }

    public function hasAuth()
    {
        return $this->request->hasHeader('Authorization');
    }

    public function getAuthData()
    {
        if ($this->request->hasHeader('Authorization')) {
            $auth = $this->request->getHeaderLine('Authorization');
            list($type, $auth) = explode(' ', $auth, 2);

            if ($type == 'Basic') {
                $auth = base64_decode($auth);
                list($username, $password) = explode(':', $auth, 2);

                return [
                    'type'     => $type,
                    'username' => $username,
                    'password' => $password
                ];
            } else if ($type == 'Digest') {
                $data = ['type' => $type];
                foreach (explode(', ', $auth) as $s) {
                    list($key, $value) = explode('=', $s);
                    $data[$key] = trim($value, '"');
                }

                return $data;
            }
        }

        return false;
    }

    public function login($username, $password, $realm = '')
    {
        $auth = $this->getAuthData();

        if (!$auth) {
            return false;
        }

        if ($auth['type'] == 'Basic') {
            return $username === $auth['username'] and $password === $auth['password'];
        }

        if ($auth['type'] == 'Digest') {
            $A1    = md5($username . ':' . $realm . ':' . $password);
            $A2    = md5($this->request->getMethod() . ':' . $auth['uri']);
            $valid = md5(implode(':', [
                $A1,
                $auth['nonce'],
                $auth['nc'],
                $auth['cnonce'],
                $auth['qop'],
                $A2
            ]));

            return ($auth['response'] === $valid);
        }

        return false;
    }
}