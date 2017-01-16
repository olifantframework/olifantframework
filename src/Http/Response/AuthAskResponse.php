<?php
namespace Olifant\Http\Response;

use Zend\Diactoros\Stream;
use Olifant\Http\Response;
use InvalidArgumentException;

class AuthAskResponse extends Response
{
    /**
     * @var string
     */
    private $realm;

    /**
     * @var array
     */
    private static $availTypes = ['Basic', 'Digest'];

    /**
     * @param integer $status  code
     * @param array   $headers list
     */
    public function __construct($status = 401, array $headers = [])
    {
        parent::__construct('php://memory', $status, $headers);
    }

    /**
     * Set realm value
     *
     * @param string $realm value
     *
     * @return self
     */
    public function setRealm($realm)
    {
        $this->realm = $realm;

        return $this;
    }

    /**
     * Get realm value
     *
     * @return string
     */
    public function getRealm()
    {
        return $this->realm;
    }

    /**
     * Set realm value
     *
     * @param string $realm value
     *
     * @return self
     */
    public function withRealm($realm)
    {
        $new = clone $this;
        $new->setRealm($realm);

        return $new;
    }

    /**
     * Set auth type
     *
     * @param string $type of auth
     *
     * @return self
     */
    public function withType($type)
    {
        $type = ucfirst(strtolower($type));

        if (!in_array($type, self::$availTypes)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported auth type \'%s\', avail types is [%s]',
                    $type,
                    implode(', ', self::$availTypes)
                )
            );
        }

        $new = clone $this;

        $realm = $new->getRealm();
        $auth = $type . ' realm="' . $realm . '"';
        if ($type === 'Digest') {
            $auth.= sprintf(
                ',qop="auth",nonce="%s",opaque="%s"',
                uniqid(true),
                md5($realm)
            );
        }

        return $new->withHeader('WWW-Authenticate', $auth);
    }

    /**
     * Set fallback text value
     *
     * @param string $text value
     *
     * @return self
     */
    public function withFallback($text)
    {
        $new = clone $this;
        $new->getBody()->write($text);

        return $new;
    }
}