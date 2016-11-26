<?php
namespace Olifant\Http;

use Dflydev\FigCookies\Cookie;
use Dflydev\FigCookies\Cookies;
use Dflydev\FigCookies\FigRequestCookies;

trait CookieRequestTrait
{
    public function getAllCookies()
    {
        return Cookies::fromRequest($this)->getAll();
    }

    public function getCookie($name)
    {
        return Cookies::fromRequest($this)->get($name);
    }

    /**
     * Check cookie exists
     *
     * @param  string  $name of cookie
     * @return boolean
     */
    public function hasCookie($name)
    {
        return Cookies::fromRequest($this)->has($name);
    }

    public function withCookie($name, $value)
    {
        $cookie = Cookie::create($name)->withValue($value);

        return FigRequestCookies::set($this, $cookie);
    }

    public function withModifiedCookie($name, callable $call)
    {
        return FigRequestCookies::modify($this, $name, $call);
    }

    /**
     * Remove cookie with specified name
     *
     * @param  string $name [description]
     * @return Olifant\Http\Request
     */
    public function withoutCookie($name)
    {
        return FigRequestCookies::remove($this, $name);
    }
}