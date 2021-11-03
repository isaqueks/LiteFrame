<?php

namespace LiteFrame\Http;

class Header {

    protected string $name;
    protected string $value;

    function __construct(string $name, string $value) {
        $this->value = $value;
        $this->setName($name);
    }

    public function value(): string {
        return $this->value;
    }

    public function setValue($value): void {
        $this->value = $value;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function name(): string {
        return $this->name;
    }

    public function is($name): bool {
        return strtoupper($name) === strtoupper($this->name);
    }

}


?>