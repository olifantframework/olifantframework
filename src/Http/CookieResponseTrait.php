<?php
namespace Olifant\Http;

use Dflydev\FigCookies\SetCookie;
use Dflydev\FigCookies\SetCookies;
use Dflydev\FigCookies\FigResponseCookies;

trait CookieResponseTrait
{
    /**
     * Set cookie
     *
     * @param string $name    of cookie
     * @param mixed  $value   of cookie
     * @param array  $options flags
     *
     * @return self
     */
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

    /**
     * Get cookie value
     *
     * @param string $name of cookie
     *
     * @return SetCookie
     */
    public function getCookie($name)
    {
        return FigResponseCookies::get($this, $name);
    }

    /**
     * Get all cookies
     *
     * @return SetCookie[]
     */
    public function getAllCookies()
    {
        return SetCookies::fromResponse($this)->getAll();
    }

    /**
     * Check cookie exists
     *
     * @param string $name of cookie
     *
     * @return boolean
     */
    public function hasCookie($name)
    {
        return SetCookies::fromResponse($this)->has($name);
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
        return FigResponseCookies::modify($this, $name, $call);
    }

    /**
     * Remove cookie from request
     *
     * @param string $name of cookie
     *
     * @return self
     */
    public function withoutCookie($name)
    {
        return FigResponseCookies::remove($this, $name);
    }

    /**
     * Force expire cookie
     *
     * @param string $name of cookie
     *
     * @return self
     */
    public function withForgetCookie($name)
    {
        return FigResponseCookies::expire($this, $name);
    }
}