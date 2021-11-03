<?php

namespace LiteFrame\Http;

use LiteFrame\Core\URL;
use LiteFrame\Http\Body\Body;
use LiteFrame\Http\Body\ReadableBody;
use LiteFrame\Http\Body\WritableBody;
use TypeError;

class Response extends Message {

    protected $setHeaderFn;
    protected $setStatusFn;
    protected int $statusCode = 200;

    function __construct(
        WritableBody $responseBody,
        callable $setHeaderFn,
        callable $setStatusFn,
        array $headers = []
    ) {

        if (!is_callable($setHeaderFn)) {
            throw new TypeError("setHeaderFn must be a callable function!");
        }
        if (!is_callable($setStatusFn)) {
            $this->setStatusFn = $setStatusFn;
        }
        
        $this->body = $responseBody;        
        $this->setAllHeaders($headers);
        
        $rawCookies = $this->header("cookie");
        if ($rawCookies) {
            $this->cookies = CookieManager::parseHeader($rawCookies);
        }
        else {
            $this->cookies = new CookieManager();
        }
    }

    public function sendHeaders(bool $commitCookies = true): void {
        if ($commitCookies) {
            $this->commitCookies();
        }

        $this->lockHead();

        call_user_func($this->setStatusFn, $this->statusCode());

        foreach ($this->cookies as $cookieName => $cookieValue) {
            call_user_func($this->setHeaderFn, $cookieName, $cookieValue);
        }

    }

    protected function end(): void {
        $this->sendHeaders(true);
        foreach ($this->cookies as $cookieName => $cookieValue) {
            call_user_func($this->setHeaderFn, $cookieName, $cookieValue);
        }
        $this->body()->end();
    }


    public function statusCode(): int {
        return $this->statusCode;
    }

    public function setStatusCode(int $code): void {
        $this->throwHeadReadOnlyIfNeeded();
        $this->statusCode = $code;
    }


}

?>