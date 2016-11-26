<?php
namespace Olifant\Http;

use Dflydev\FigCookies\SetCookie;
use Dflydev\FigCookies\SetCookies;
use Dflydev\FigCookies\FigResponseCookies;

trait CookieResponseTrait
{
    public function withCookie($name, $value, array $options = [])
    {
        $cookie = SetCookie::create($name)->withValue($value);

        if (isset($options['expires'])) {
            $cookie = $cookie->withExpires($options['expires']);
        }

        if (isset($options['maxage'])) {
            $cookie = $cookie->withMaxAge($options['maxage']);
        }

        if (isset($options['path'])) {
            $cookie = $cookie->withPath($options['path']);
        }

        if (isset($options['domain'])) {
            $cookie = $cookie->withDomain($options['domain']);
        }

        if (isset($options['secure'])) {
            $cookie = $cookie->withSecure($options['secure']);
        }

        if (isset($options['httponly'])) {
            $cookie = $cookie->withHttpOnly($options['httponly']);
        }

        return FigResponseCookies::set($this, $cookie);
    }

    public function getCookie($name)
    {
        return FigResponseCookies::get($this, $name);
    }

    public function getAllCookies()
    {
        return SetCookies::fromResponse($this)->getAll();
    }

    public function hasCookie($name)
    {
        return SetCookies::fromResponse($this)->has($name);
    }

    public function withModifiedCookie($name, callable $call)
    {
        return FigResponseCookies::modify($this, $name, $call);
    }

    public function withoutCookie($name)
    {
        return FigResponseCookies::remove($this, $name);
    }

    public function withForgetCookie($name)
    {
        return FigResponseCookies::expire($this, $name);
    }
}