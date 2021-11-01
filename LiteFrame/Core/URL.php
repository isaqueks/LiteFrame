<?php

namespace LiteFrame\Core;

class URL {

    protected string $href;
    protected string $queryString;
    protected string $path;
    protected array $queryParams;

    public function __construct(string $url) {
        $this->href = $url;
        $this->queryParams = [];
        $queryPos = strpos($url, "?");

        if ($queryPos !== false) {
            $this->path = substr($url, 0, $queryPos);
            $this->queryString = substr($url, $queryPos+1);
            mb_parse_str($this->queryString, $this->queryParams);
        }
        else {
            $this->path = $url;
            $this->queryString = "";
        }
    }

    public function href(): string {
        return $this->href;
    }

    public function path(): string {
        return $this->path;
    }

    public function queryString(): string {
        return $this->queryString;
    }

    public function queryParam(string $param): string|null {
        return $this->queryParams[$param] ?? null;
    }
}

?>