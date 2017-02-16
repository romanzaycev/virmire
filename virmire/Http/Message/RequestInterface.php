<?php declare(strict_types = 1);

namespace Virmire\Http\Message;

/**
 * Representation of an outgoing, client-side request.
 *
 * @package Virmire\Interfaces\Http\Message
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * @return string
     */
    public function getRequestTarget() : string;
    
    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     *
     * @param mixed $requestTarget
     *
     * @return RequestInterface
     */
    public function withRequestTarget($requestTarget) : RequestInterface;
    
    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod() : string;
    
    /**
     * Return an instance with the provided HTTP method.
     *
     * @param string $method Case-sensitive method.
     *
     * @return RequestInterface
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod(string $method) : RequestInterface;
    
    /**
     * Retrieves the URI instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri() : UriInterface;
    
    /**
     * Returns an instance with the provided URI.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     *
     * @param UriInterface $uri  New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     *
     * @return RequestInterface
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false) : RequestInterface;
}
