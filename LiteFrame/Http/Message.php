<?php

namespace LiteFrame\Http;

use LiteFrame\Exceptions\FormatException;
use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Http\Body\Body;

abstract class Message {
    protected array $headers;
    protected CookieManager $cookies;
    protected Body $body;
    protected bool $headersLocked = false;


    protected function throwHeadersReadOnlyIfNeeded() {
        if ($this->headersLocked === true) {
            throw new ReadOnlyException("Headers are read-only.");
        }
    }


    protected function setAllHeaders(array $headers) {
        $this->headers = [];
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
    }
    
    public function setHeader(string $name, string $content) {
        $this->throwHeadersReadOnlyIfNeeded();
        $this->headers[strtoupper($name)] = $content;
    }

    public function header(string $name): string|false {
        return $this->headers[strtoupper($name)] ?? false;
    }

    public function getAllHeaders(): array {
        $copy = $this->headers;
        return $copy;
    }

    public function cookie(string $name): string|null {
        return $this->cookies->get($name);
    }

    public function setCookie(string $name, string $value): void {
        $this->throwHeadersReadOnlyIfNeeded();
        $this->cookies->set($name, $value);
    }

    public function getAllCookies(): array {
        return $this->cookies->getAll();
    }

    public function body(): Body {
        return $this->body;
    }


    public function lockHeaders() {
        $this->headersLocked = true;
    }

}

?>