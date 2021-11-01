<?php

namespace LiteFrame\Http;

class Cookie {

    public string $name;
    public string $value;

    protected array $attributes;

    function __construct(string $name, string $value, array $attrs = []) {
        $this->name = $name;
        $this->value = $value;
        $this->attributes = $attrs;
    }

    public function setAttribute(string $attr, string $val) {
        $this->attributes[$attr] = $val;
    }

    public function attribute(string $attr): string|null {
        return $this->attributes[$attr] ?? null;
    }

    public function getAllAttributes(): array {
        return $this->attributes;
    }

}


?>