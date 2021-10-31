<?php

namespace LiteFrame\Http\Body;

abstract class Body {

    public abstract function read(): string;
    public abstract function readJson(): array;
    public abstract function readFormData(): array;
    public abstract function readURLEncoded(): array;

    public abstract function write(string $content): void;
    public abstract function writeJson(array $json): void;


}

?>