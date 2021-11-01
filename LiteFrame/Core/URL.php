<?php

namespace LiteFrame\Core;

class URL {

    protected string $href;

    public function __construct(string $url) {
        $this->href = $url;
    }

    public function href(): string {
        return $this->href;
    }

}

?>