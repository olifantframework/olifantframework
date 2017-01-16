<?php
namespace Olifant\Http;

use Dflydev\FigCookies\Cookie;
use Dflydev\FigCookies\Cookies;
use Dflydev\FigCookies\FigRequestCookies;

trait CookieRequestTrait
{
    /**
     * Get all cookies
     *
     * @return Cookie[]
     */
    public function getAllCookies()
    {
        return Cookies::fromRequest($this)->getAll();
    }

    /**
     * Get cookie
     *
     * @param string $name of cookie
     *
     * @return @return Cookie|null
     */
    public function getCookie($name)
    {
        return Cookies::fromRequest($this)->get($name);
    }

    /**
     * Check cookie exists
     *
     * @param  string  $name of cookie
     *
     * @return boolean
     */
    public function hasCookie($name)
    {
        return Cookies::fromRequest($this)->has($name);
    }

    /**
     * Set cookie
     *
     * @param string $name  of cookie
     * @param mixed $value of cookie
     *
     * @return self
     */
    public function withCookie($name, $value)
    {
        $cookie = Cookie::create($name)->withValue($value);

        return FigRequestCookies::set($this, $cookie);
    }

    /**
     * Modify cookie value
     *
     * @param string   $name of cookie
     * @param callable $call resolver
     *
     * @return self
     */
    public function withModifiedCookie($name, callable $call)
    {
        return FigRequestCookies::modify($this, $name, $call);
    }

    /**
     * Remove cookie with specified name
     *
     * @param string $name of cookie
     *
     * @return self
     */
    public function withoutCookie($name)
    {
        return FigRequestCookies::remove($this, $name);
    }
}