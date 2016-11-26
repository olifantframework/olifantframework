<?php
namespace Olifant\Http\Response;

use Olifant\Http\Response;
use Zend\Diactoros\Stream;
use InvalidArgumentException;

class JsonResponse extends Response
{
    /**
     * Default flags for json_encode; value of:
     *
     * <code>
     * JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES
     * </code>
     *
     * @const int
     */
    const DEFAULT_JSON_FLAGS = 79;

    public function __construct($status = 200, array $headers= [])
    {
        parent::__construct($status, $headers);
    }

    public function withData($data, $options = self::DEFAULT_JSON_FLAGS)
    {
        $json = $this->jsonEncode($data, $options);

        $body = new Stream('php://temp', 'wb+');
        $body->write($json);
        $body->rewind();

        $new = clone $this;
        if (!$new->hasHeader('content-type')) {
            $new = $new->withHeader('content-type', 'application/json');
        }

        $new = $new->withBody($body);

        return $new;
    }

    /**
     * Encode the provided data to JSON.
     *
     * @param mixed $data
     * @param int $encodingOptions
     * @return string
     * @throws InvalidArgumentException if unable to encode the $data to JSON.
     */
    private function jsonEncode($data, $encodingOptions)
    {
        if (is_resource($data)) {
            throw new InvalidArgumentException('Cannot JSON encode resources');
        }

        // Clear json_last_error()
        json_encode(null);

        $json = json_encode($data, $encodingOptions);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(sprintf(
                'Unable to encode data to JSON in %s: %s',
                __CLASS__,
                json_last_error_msg()
            ));
        }

        return $json;
    }
}