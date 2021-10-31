<?php

namespace LiteFrame\Http;

use LiteFrame\Http\Body\ReadableBody;
use TypeError;

class Request extends Message {

    protected string $url;
    protected string $method;

    function __construct(
        string $url, 
        string $method, 
        string $requestBody, 
        array|string $cookies = null, 
        array $headers = []
    ) {
        $this->url = $url;
        $this->method = $method;
        $this->body = new ReadableBody($requestBody);
        $this->setAllHeaders($headers);
        
        $cookiesType = gettype($cookies);
        if ($cookiesType === "string") {
            $this->parseAllCookies($cookies);
        }
        else if ($cookiesType === "array") {
            $this->setAllCookies($cookies);
        }
        else if ($cookiesType === "NULL") {
            $newCookies = $this->header("cookie");
            if ($newCookies) {
                $this->parseAllCookies($newCookies);
            }
        }
        else {
            throw new TypeError("Cookies should be a string, array or null!");
        }
    }

    public function url(): string {
        return $this->url;
    }

    public function method(): string {
        return $this->method;
    }

    static function current(): Request {
        $headers = getallheaders() ?? [];
        $url = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];
        $body = file_get_contents("php://input") ?? "";

        return new Request($url, $method, $body, null, $headers);
    }

}

?>