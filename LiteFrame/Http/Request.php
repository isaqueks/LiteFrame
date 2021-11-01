<?php

namespace LiteFrame\Http;

use LiteFrame\Core\URL;
use LiteFrame\Http\Body\ReadableBody;
use TypeError;

class Request extends Message {

    protected URL $url;
    protected string $method;

    function __construct(
        string $url, 
        string $method, 
        string $requestBody, 
        array|string|null $cookies = null, 
        array $headers = []
    ) {
        $this->url = new URL($url);
        $this->method = strtoupper($method);
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

    public function url(): URL {
        return $this->url;
    }

    public function href(): string {
        return $this->url()->href();
    }

    public function method(): string {
        return $this->method;
    }

}

?>