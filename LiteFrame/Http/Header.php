<?php

namespace LiteFrame\Http;

class Header {

    protected string $name;
    public string $value;

    function __construct(string $name, string $value) {
        $this->value = $value;
        $this->setName($name);
    }

    public function setName($name): void {
        $this->name = strtoupper($name);
    }

    public function name(): string {
        return $this->name;
    }

    public function is($name): bool {
        return strtoupper($name) === $this->name;
    }

}


?>