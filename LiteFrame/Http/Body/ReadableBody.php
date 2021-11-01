<?php

namespace LiteFrame\Http\Body;

use Exception;
use LiteFrame\Exceptions\FormatException;
use LiteFrame\Exceptions\ReadOnlyException;

class ReadableBody extends Body {

    protected string $content;

    function __construct(string $rawContent) {
        $this->content = $rawContent;
    }

    private function throwReadOnly(string $msg = "Cannot write to read-only body!") {
        throw new ReadOnlyException($msg, 1);
    }


    public function read(): string {
        return $this->content;
    }

    public function write(string $content): void {
        $this->throwReadOnly();
    }

    public function end(): void {
        $this->throwReadOnly();
    }

}

?>