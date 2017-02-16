<?php declare(strict_types = 1);

namespace Virmire\Iterfaces\Http\Message;

/**
 * Representation of an incoming, server-side HTTP request.
 *
 * @package Virmire\Interfaces\Http\Message
 */
interface ServerRequestInterface extends RequestInterface
{
    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal.
     *
     * @return array
     */
    public function getServerParams() : array;
    
    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * @return array
     */
    public function getCookieParams() : array;
    
    /**
     * Return an instance with the specified cookies.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     *
     * @return ServerRequestInterface
     */
    public function withCookieParams(array $cookies) : ServerRequestInterface;
    
    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments.
     *
     * @return string[]
     */
    public function getQueryParams() : array;
    
    /**
     * Return an instance with the specified query string arguments.
     *
     * @param array $query Array of query string arguments, typically from $_GET.
     *
     * @return ServerRequestInterface
     */
    public function withQueryParams(array $query) : ServerRequestInterface;
    
    /**
     * Retrieve normalized file upload data.
     *
     * This method returns upload metadata in a normalized tree, with each leaf
     * an instance of Virmire\Interfaces\Http\Message\UploadedFileInterface.
     *
     * @return UploadedFileInterface[] An array tree of UploadedFileInterface instances;
     */
    public function getUploadedFiles() : array;
    
    /**
     * Create a new instance with the specified uploaded files.
     *
     * @param UploadedFileInterface[] $uploadedFiles An array tree of UploadedFileInterface instances.
     *
     * @return ServerRequestInterface
     * @throws \InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles) : ServerRequestInterface;
    
    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return string[] deserialized body parameters.
     */
    public function getParsedBody() : array;
    
    /**
     * Return an instance with the specified body parameters.
     *
     * @param null|array $data The deserialized body data.
     *
     * @return ServerRequestInterface
     * @throws \InvalidArgumentException if an unsupported argument type is provided.
     */
    public function withParsedBody($data) : ServerRequestInterface;
    
    /**
     * Retrieve attributes derived from the request.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes() : array;
    
    /**
     * Retrieve a single derived request attribute.
     *
     * @see getAttributes()
     *
     * @param string $name   The attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     *
     * @return mixed
     */
    public function getAttribute(string $name, $default = null);
    
    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * @see getAttributes()
     *
     * @param string $name The attribute name.
     * @param mixed $value The value of the attribute.
     *
     * @return ServerRequestInterface
     */
    public function withAttribute(string $name, $value) : ServerRequestInterface;
    
    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * @see getAttributes()
     *
     * @param string $name The attribute name.
     *
     * @return ServerRequestInterface
     */
    public function withoutAttribute(string $name) : ServerRequestInterface;
}
