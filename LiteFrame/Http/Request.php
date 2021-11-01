<?php

namespace LiteFrame\Http;

use LiteFrame\Core\URL;
use LiteFrame\Http\Body\Body;
use LiteFrame\Http\Body\ReadableBody;
use TypeError;

class Request extends Message {

    protected URL $url;
    protected string $method;

    function __construct(
        string $url, 
        string $method, 
        string|Body $requestBody, 
        array $headers = []
    ) {
        $this->url = new URL($url);
        $this->method = strtoupper($method);
        

        $bodyType = gettype($requestBody);

        if (
            $bodyType === "object" && 
            ($requestBody::class === Body::class ||
            is_subclass_of($requestBody, Body::class))
        ) {
            $this->body = $requestBody;
        }
        else if ($bodyType === "string") {
            $this->body = new ReadableBody($requestBody);
        }
        else {
            throw new TypeError("requestBody should be a Body instance or string!");
        }

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