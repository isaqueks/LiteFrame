<?php

namespace LiteFrame\Http;

use Exception;
use LiteFrame\Exceptions\FormatException;
use LiteFrame\Http\Body\Body;

abstract class Message {
    protected array $headers;
    protected array $cookies;
    protected Body $body;

    protected function setAllHeaders(array $headers) {
        $this->headers = [];
        foreach ($headers as $name => $value) {
            $this->headers[strtoupper($name)] = $value;
        }
    }
    
    public function setHeader(string $name, string $content) {
        $this->headers[strtoupper($name)] = $content;
    }

    public function header(string $name): string|false {
        return $this->headers[strtoupper($name)] ?? false;
    }

    public function getAllHeaders(): array {
        $copy = $this->headers;
        return $copy;
    }


    public function parseAllCookies(string $cookies): void {
        $arr = [];
        $cookiesSplit = explode(";", $cookies);

        foreach ($cookiesSplit as $value) {
            $parsed = [];
            if (mb_parse_str($value, $parsed) === false) {
                throw new FormatException("Unable to parse cookies! Got '$cookies' ($value).");
            }
            foreach ($parsed as $cookieName => $cookieValue) {
                $arr[$cookieName] = $cookieValue;
            }
        }

        $this->setAllCookies($arr);

    }

    public function setAllCookies(array $cookies): void {
        $this->cookies = $cookies;
    }

    public function cookie(string $name): string|false {
        return $this->cookies[$name] ?? false;
    }

    public function setCookie(string $name, string $value): void {
        $this->cookies[$name] = $value;
    }

    public function getAllCookies(): array {
        $copy = $this->cookies;
        return $copy;
    }

    public function body(): Body {
        return $this->body;
    }


}

?>