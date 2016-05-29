<?php
namespace pillr\library\http;


use \Psr\Http\Message\ResponseInterface as ResponseInterface;

use \pillr\library\http\Message         as  Message;

/**
 * Representation of an outgoing, server-side response.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - Status code and reason phrase
 * - Headers
 * - Message body
 *
 * Responses are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */
class Response extends Message implements ResponseInterface
{
    protected $_statusCode;
    protected $_reasonPhrase;
    protected $_http_status_codes = array(
        200 => "OK",
        404 => "Not Found",
        500 => "Internal Server Error"
    );

    /**
     * Response constructor.
     * @param $protocolVersion
     * @param $statusCode
     * @param $reasonPhrase
     * @param $headers
     * @param $messageBody
     */
    function __construct($protocolVersion, $statusCode, $reasonPhrase = '', $headers, $messageBody)
    {
        $this->protocolVersion = $protocolVersion;
        $this->_statusCode = $statusCode;
        $this->_reasonPhrase = ($reasonPhrase == '') ? $this->getReasonPhrase() : $reasonPhrase;
        $this->headers = $headers;
        $this->messageBody = $messageBody;
    }

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @return self
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->_statusCode = $code;
        $this->_reasonPhrase = $reasonPhrase;
        return $this;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be empty. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase()
    {
        foreach($this->_http_status_codes as $code => $phrase){
            if($this->_statusCode == $code){
                $this->_reasonPhrase = $phrase;
            }
        }
        return $this->_reasonPhrase;
    }
}