<?php declare(strict_types = 1);

namespace Virmire\Http\Message;

/**
 * Representation of an outgoing, server-side response.
 *
 * @package Virmire\Interfaces\Http\Message
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode() : int;
    
    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @param int $code            The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the provided status code;
     *
     * @return ResponseInterface
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus(int $code, string $reasonPhrase = '') : ResponseInterface;
    
    /**
     * Gets the response reason phrase associated with the status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase;
     */
    public function getReasonPhrase() : string;
}
