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
        array $headers = []
    ) {
        $this->url = new URL($url);
        $this->method = strtoupper($method);
        $this->body = new ReadableBody($requestBody);
        $this->setAllHeaders($headers);
        
        $rawCookies = $this->header("cookie");
        if ($rawCookies) {
            $this->cookies = CookieManager::parseHeader($rawCookies);
        }
        else {
            $this->cookies = new CookieManager();
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