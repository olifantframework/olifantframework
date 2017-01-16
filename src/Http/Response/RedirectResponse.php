<?php
namespace Olifant\Http\Response;

use Olifant\Http\Response;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class RedirectResponse extends Response
{
    /**
     * @param integer $status  code
     * @param array   $headers list
     */
    public function __construct($status = 302, array $headers = [])
    {
        parent::__construct($status, $headers);
    }

    /**
     * Set target url
     *
     * @param string|UriInterface $uri instance
     *
     * @return self
     */
    public function withRedirectUri($uri)
    {
        if (! is_string($uri) && ! $uri instanceof UriInterface) {
            throw new InvalidArgumentException(sprintf(
                'Uri provided to %s MUST be a string or ' .
                'Psr\Http\Message\UriInterface instance; received "%s"',
                __CLASS__,
                (is_object($uri) ? get_class($uri) : gettype($uri))
            ));
        }

        $new = clone $this;
        $new = $new->withHeader('location', (string)$uri);

        return $new;
    }
}