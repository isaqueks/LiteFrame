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

    public function read(): string {
        return $this->content;
    }

    public function readJson(): array {
        $result = json_decode($this->read(), true);
        if (!$result) {
            throw new FormatException(json_last_error_msg(), 1);
        }
        return $result;
    }

    public function readURLEncoded(): array {
        $result = [];
        if (mb_parse_str($this->read(), $result) === false) {
            throw new FormatException();
        }
        return $result;
    }

    public function readFormData(): array {
        throw new Exception("Not implemented!", 1);
    }


    private function throwReadOnly() {
        throw new ReadOnlyException("Cannot write to read-only body!", 1);
    }

    public function write(string $content): void {
        $this->throwReadOnly();
    }

    public function writeJson(array $json): void {
        $this->throwReadOnly();
    }

}

?>