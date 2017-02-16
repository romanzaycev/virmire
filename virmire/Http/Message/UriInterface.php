<?php declare(strict_types = 1);

namespace Virmire\Iterfaces\Http\Message;

/**
 * Value object representing a URI.
 *
 * @link    http://tools.ietf.org/html/rfc3986 (the URI specification)
 * @package Virmire\Interfaces\Http\Message
 */
interface UriInterface
{
    /**
     * Retrieve the scheme component of the URI.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string The URI scheme.
     */
    public function getScheme() : string;
    
    /**
     * Retrieve the authority component of the URI.
     *
     * The authority syntax of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority() : string;
    
    /**
     * Retrieve the user information component of the URI.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo() : string;
    
    /**
     * Retrieve the host component of the URI.
     *
     * If no host is present, this method return an empty string.
     *
     * The value returned normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string The URI host.
     */
    public function getHost() : string;
    
    /**
     * Retrieve the port component of the URI.
     *
     * @return null|int The URI port.
     */
    public function getPort();
    
    /**
     * Retrieve the path component of the URI.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string The URI path.
     */
    public function getPath() : string;
    
    /**
     * Retrieve the query string of the URI.
     *
     * If no query string is present, this method return an empty string.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string The URI query string.
     */
    public function getQuery() : string;
    
    /**
     * Retrieve the fragment component of the URI.
     *
     * If no fragment is present, this method return an empty string.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string The URI fragment.
     */
    public function getFragment() : string;
    
    /**
     * Return an instance with the specified scheme.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     *
     * @return UriInterface A new instance with the specified scheme.
     * @throws \InvalidArgumentException for invalid or unsupported schemes.
     */
    public function withScheme(string $scheme) : UriInterface;
    
    /**
     * Return an instance with the specified user information.
     *
     * @param string $user          The user name to use for authority.
     * @param null|string $password The password associated with $user.
     *
     * @return UriInterface A new instance with the specified user information.
     */
    public function withUserInfo(string $user, $password = null) : UriInterface;
    
    /**
     * Return an instance with the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     *
     * @return UriInterface A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host) : UriInterface;
    
    /**
     * Return an instance with the specified port.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port to use with the new instance; a null value removes the port information.
     *
     * @return UriInterface A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port) : UriInterface;
    
    /**
     * Return an instance with the specified path.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     *
     * @return UriInterface A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path) : UriInterface;
    
    /**
     * Return an instance with the specified query string.
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     *
     * @return UriInterface A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
    public function withQuery($query) : UriInterface;
    
    /**
     * Return an instance with the specified URI fragment.
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     *
     * @return UriInterface A new instance with the specified fragment.
     */
    public function withFragment($fragment) : UriInterface;
    
    /**
     * Return the string representation as a URI reference.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     * @return string
     */
    public function __toString() : string;
}
