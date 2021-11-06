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
            throw new TypeError("setStatusFn must be a callable function!");
        }

        $this->setHeaderFn = $setHeaderFn;
        $this->setStatusFn = $setStatusFn;
        
        $this->body = $responseBody;        
        $this->setAllHeaders($headers);
        $this->cookies = CookieManager::parseSetCookieHeader($this->headers->getAllRaw());

    }

    public function sendHeaders(bool $commitCookies = true): void {
        if ($commitCookies) {
            $this->commitCookies();
        }

        $this->lockHead();

        call_user_func($this->setStatusFn, $this->statusCode());

        $headers = $this->headers->getAllRaw();

        foreach ($headers as $header) {
            call_user_func($this->setHeaderFn, $header->name(), $header->value());
        }

    }

    public function end(): void {
        $this->sendHeaders(true);
        $this->body()->end();
    }


    public function statusCode(): int {
        return $this->statusCode;
    }

    public function setStatusCode(int $code): void {
        $this->throwHeadReadOnlyIfNeeded();
        $this->statusCode = $code;
    }

    public function view(string $path, array $params = []): void {
        extract($params);
        ob_start();
        require $path;
        $content = ob_get_clean();
        $this->body()->write($content);
    }


}

?>