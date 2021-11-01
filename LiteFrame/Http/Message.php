<?php

namespace LiteFrame\Http;

use LiteFrame\Exceptions\FormatException;
use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Http\Body\Body;

abstract class Message {
    protected HeaderManager $headers;
    protected CookieManager $cookies;
    protected Body $body;
    protected bool $headLocked = false;
    protected int $statusCode;


    protected function throwHeadReadOnlyIfNeeded() {
        if ($this->headLocked === true) {
            throw new ReadOnlyException("Head is read-only.");
        }
    }


    protected function setAllHeaders(array $headers) {
        $this->headers = new HeaderManager($headers);
        // foreach ($headers as $name => $value) {
        //     $this->setHeader($name, $value);
        // }
    }
    
    public function setHeader(string $name, string $content, bool $replace_UNSAFE = true) {
        $this->throwHeadReadOnlyIfNeeded();
        $this->headers->set($name, $content, $replace_UNSAFE);
    }

    public function header(string $name): string|null {
        return $this->headers->get($name) ?? null;
    }


    /**
     * @return Header[]
     */
    public function getAllHeaders(): array {
        return $this->headers->getAllRaw();
    }


    public function commitCookies(): void {
        $headers = $this->cookies->toHeadersString();
        foreach ($headers as $content) {
            $this->setHeader("Set-Cookie", $content, false);
        }
    }

    public function cookie(string $name): string|null {
        return $this->cookies->get($name);
    }

    public function setCookie(string $name, string $value, bool $autoCommit = true): void {
        $this->throwHeadReadOnlyIfNeeded();
        $this->cookies->set($name, $value);
        if ($autoCommit) {
            $this->commitCookies();
        }
    }

    public function getAllCookies(): array {
        return $this->cookies->getAll();
    }


    public function statusCode(): int {
        return $this->statusCode;
    }

    public function setStatusCode(int $code): void {
        $this->throwHeadReadOnlyIfNeeded();
        $this->statusCode = $code;
    }


    public function body(): Body {
        return $this->body;
    }

    public function lockHead() {
        $this->headLocked = true;
    }

}

?>