<?php declare(strict_types = 1);

namespace Virmire\Http;

/**
 * Class Response
 *
 * @package Virmire\Http
 */
class Response
{

    /**
     * @var int
     */
    private $statusCode = 200;

    /**
     * @var array
     */
    private $headers = [
        'Content-Type' => 'text/html; charset=UTF-8'
    ];

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var bool
     */
    private $isRedirect = false;

    /**
     * @param int $code
     *
     * @return Response
     * @throws HttpException
     */
    public function setStatusCode(int $code) : Response
    {
        if ((100 > $code) || (599 < $code)) {
            throw new HttpException(
                sprintf('Invalid HTTP response code, %u', $code)
            );
        }

        $this->isRedirect = (300 <= $code) && (307 >= $code);
        $this->statusCode = $code;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRedirect() : bool
    {
        return $this->isRedirect;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return Response
     */
    public function setHeader(string $name, string $value) : Response
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * @return Response
     */
    public function writeHeaders() : Response
    {
        if(!headers_sent()){
            $statusCodeSent = false;

            if (count($this->headers) == 0) {
                return $this;
            }

            foreach ($this->headers as $headerName => $headerValue) {
                if (!$statusCodeSent) {
                    header(
                        sprintf('%s: %s', $headerName, $headerValue),
                        false,
                        $this->statusCode
                    );

                    $statusCodeSent = true;
                } else {
                    header(
                        sprintf('%s: %s', $headerName, $headerValue)
                    );
                }
            }
        }

        return $this;
    }

    /**
     * @return Response
     */
    public function writeBody() : Response
    {
        echo $this->body;

        return $this;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * @return void
     */
    public function sendResponse()
    {
        $this->writeHeaders()->writeBody();
    }

}