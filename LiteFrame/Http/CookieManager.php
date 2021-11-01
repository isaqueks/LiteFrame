<?php

namespace LiteFrame\Http;

use Exception;
use LiteFrame\Exceptions\FormatException;

class CookieManager {

    protected array $cookies;

    public function __construct(array $cookies = []) {
        $this->cookies = $cookies;
    }

    public function set(string $name, string $value): void {
        $this->cookies[$name] = $value;
    } 

    public function get(string $name): string|null {
        return $this->cookies[$name] ?? null;
    }

    public function toHeaderString(): string {
        throw new Exception("Not implemented");
    }

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