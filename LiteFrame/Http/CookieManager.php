<?php

namespace LiteFrame\Http;

class CookieManager {

    /**
     * @var Cookie[] cookies
     */
    protected array $cookies;

    /**
     * @param Cookie[]|array[string]string cookies
     * @param bool isAssociative True if `cookies` is an 
     * associative array, false if cookies is an array 
     * of `Cookies`
     */
    public function __construct(array $cookies = [], bool $isAssociative = true) {
        if ($isAssociative) {

            $this->cookies = [];

            foreach ($cookies as $name => $value) {
                $this->set($name, $value, []);
            }

        }
        else {
            $this->cookies = $cookies;
        }
    }

    public function setRaw(Cookie $cookie): void {
        $this->cookies[$cookie->name()] = $cookie;
    }

    public function set(string $name, string $value, array $attrs = []): void {
        if (isset($this->cookies[$name])) {
            $cook = $this->cookies[$name];
            $cook->setValue($value);
            $cook->setAllAttributes($attrs);
        }
        else {
            $this->cookies[$name] = new Cookie($name, $value, $attrs);
        }
    } 

    public function remove(string $name): void {
        unset($this->cookies[$name]);
    }

    public function get(string $name): Cookie|null {
        return $this->cookies[$name] ?? null;
    }

    public function getCookieValue($name): string|null {
        return $this->cookies[$name]?->value() ?? null;
    }

    public function toHeadersString(): array {
        $arr = [];

        foreach ($this->cookies as $name => $cookie) {
            array_push($arr, $cookie->toHeaderString());
        }

        return $arr;
    }


    /**
     * @return Cookie[]
     */
    public function getAll(): array {
        return $this->cookies;
    }


    public static function parseHeader(string $content): CookieManager {
        $cookies = new CookieManager();
        $cookiesSplit = explode(";", $content);

        foreach ($cookiesSplit as $value) {
            $parsed = [];
            if (mb_parse_str($value, $parsed) === false) {
                // Since the user can manipulate the cookies,
                // I don't thinks it's good to throw an Exception
                // throw new FormatException("Unable to parse cookies! Got '$content' ($value).");
                continue;
            }
            foreach ($parsed as $cookieName => $cookieValue) {
                $cookies->set($cookieName, $cookieValue);
            }
        }

        return $cookies;
    }

}

?>