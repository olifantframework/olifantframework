<?php
namespace Olifant\Http;

use InvalidArgumentException;
use UnexpectedValueException;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\ServerRequest as ZendServerRequest;

/**
 * @method mixed get(string $key) get query param
 */
class ServerRequest extends ZendServerRequest
{
    use CookieRequestTrait;

    /**
     * @see Zend\Diactoros\ServerRequest
     */
    public function __construct()
    {
        call_user_func_array(['parent','__construct'], func_get_args());
    }

    /**
     * Create a request from the supplied superglobal values.
     *
     * If any argument is not supplied, the corresponding superglobal value will
     * be used.
     *
     * The ServerRequest created is then passed to the fromServer() method in
     * order to marshal the request URI and headers.
     *
     * @see fromServer()
     * @param array $server $_SERVER superglobal
     * @param array $query $_GET superglobal
     * @param array $body $_POST superglobal
     * @param array $cookies $_COOKIE superglobal
     * @param array $files $_FILES superglobal
     * @return ServerRequest
     * @throws InvalidArgumentException for invalid file values
     */
    public static function fromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null
    ) {
        $server  = ServerRequestFactory::normalizeServer($server ?: $_SERVER);
        $files   = ServerRequestFactory::normalizeFiles($files ?: $_FILES);
        $headers = ServerRequestFactory::marshalHeaders($server);

        return new self(
            $server,
            $files,
            ServerRequestFactory::marshalUriFromServer($server, $headers),
            ServerRequestFactory::get('REQUEST_METHOD', $server, 'GET'),
            'php://input',
            $headers,
            $cookies ?: $_COOKIE,
            $query ?: $_GET,
            $body ?: $_POST,
            self::marshalProtocolVersion($server)
        );
    }

    /**
     * Return HTTP protocol version (X.Y)
     *
     * @param array $server
     * @return string
     */
    private static function marshalProtocolVersion(array $server)
    {
        if (! isset($server['SERVER_PROTOCOL'])) {
            return '1.1';
        }

        if (! preg_match('#^(HTTP/)?(?P<version>[1-9]\d*(?:\.\d)?)$#', $server['SERVER_PROTOCOL'], $matches)) {
            throw new UnexpectedValueException(sprintf(
                'Unrecognized protocol version (%s)',
                $server['SERVER_PROTOCOL']
            ));
        }

        return $matches['version'];
    }

    public static function build($uri = null)
    {
        return new ClientRequest($uri);
    }

    public function getClientInfo()
    {
        return new ClientInfo($this);
    }

    /**
     * Detect JSON request
     *
     * @return boolean
     */
    public function isJson()
    {
        $type = $this->getHeaderLine('Content-Type');

        return 0 === strpos($type, 'application/json');
    }

    /**
     * Parse JSON request
     *
     * @param boolean $assoc When TRUE, returned objects will be converted into associative arrays
     *
     * @return mixed
     */
    public function getJson($assoc = false)
    {
        // reset error
        json_encode(null);

        $json = $this->getBody()->getContents();
        $json = json_decode($json, $assoc);

        if (JSON_ERROR_NONE != json_last_error()) {
            throw new InvalidArgumentException(sprintf(
                'Unable to decode data from JSON in %s: %s',
                __CLASS__,
                json_last_error_msg()
            ));
        }

        return $json;
    }

    /**
     * Detect AJAX request
     *
     * @return boolean
     */
    public function isAjax()
    {
        if (!$this->hasHeader('X-Requested-With')) {
            return false;
        }

        $requestedWith = $this->getHeaderLine('X-Requested-With');

        return 'xmlhttprequest' === strtolower($requestedWith);
    }

    /**
     * Detect secure connection
     *
     * @return boolean
     */
    public function isSecure()
    {
        return 'https' === $this->getUri()->getScheme();
    }

    /**
     * Check if file
     *
     * @param string $name of
     *
     * @return boolean
     */
    public function hasFile($name)
    {
        $files = $this->getUploadedFiles();

        return isset($files[$name]);
    }

    /**
     * Get query params from different request types
     *
     * @return mixed
     */
    private function getQuerySource()
    {
        $source = [];
        $method = $this->getMethod();
        if ($method == 'GET') {
            $source = $this->getQueryParams();
        } else if (in_array($method, ['POST','PUT','PATCH'])) {
            $source = $this->getParsedBody();
        }

        return $source;
    }

    /**
     * Check if request param exists
     *
     * @param strinf $key name
     *
     * @return boolean
     */
    public function has($key)
    {
        $source = $this->getQuerySource();

        return isset($source[$key]);
    }

    /**
     * Get request params
     *
     * @return mixed
     */
    public function get()
    {
        $args = func_get_args();
        if (count($args) > 1) {
            $list = [];
            foreach ($args as $key) {
                $list[] = $this->get($key);
            }

            return $list;
        }

        $source = $this->getQuerySource();

        if (!$args) return $source;

        $key = reset($args);
        if (isset($source[$key])) {
            return $source[$key];
        }
    }
}