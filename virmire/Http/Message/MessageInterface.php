<?php declare(strict_types = 1);

namespace Virmire\Http\Message;

/**
 * HTTP messages consist of requests from a client to a server and responses
 * from a server to a client. This interface defines the methods common to
 * each.
 *
 * @link    http://www.ietf.org/rfc/rfc7230.txt
 * @link    http://www.ietf.org/rfc/rfc7231.txt
 * @package Virmire\Interfaces\Http\Message
 */
interface MessageInterface
{
    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion() : string;
    
    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * @param string $version HTTP protocol version
     *
     * @return MessageInterface
     */
    public function withProtocolVersion(string $version) : MessageInterface;
    
    /**
     * Retrieves all message header values.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * While header names are not case-sensitive, getHeaders() will preserve the
     * exact case in which headers were originally specified.
     *
     * @return string[][] Returns an associative array of the message's headers.
     */
    public function getHeaders() : array;
    
    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return bool
     */
    public function hasHeader(string $name) : bool;
    
    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return string[] An array of string values as provided for the given
     *    header.
     */
    public function getHeader(string $name) : array;
    
    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma.
     */
    public function getHeaderLine(string $name) : string;
    
    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * While header names are case-insensitive, the casing of the header will
     * be preserved by this function, and returned from getHeaders().
     *
     * @param string $name           Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     *
     * @return MessageInterface
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader(string $name, $value) : MessageInterface;
    
    /**
     * Return an instance with the specified header appended with the given value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * @param string $name           Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     *
     * @return MessageInterface
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withAddedHeader(string $name, $value) : MessageInterface;
    
    /**
     * Return an instance without the specified header.
     *
     * @param string $name Case-insensitive header field name to remove.
     *
     * @return MessageInterface
     */
    public function withoutHeader(string $name) : MessageInterface;
    
    /**
     * Gets the body of the message.
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody() : StreamInterface;
    
    /**
     * Return an instance with the specified message body.
     *
     * @param StreamInterface $body Body.
     *
     * @return MessageInterface
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body) : MessageInterface;
}
