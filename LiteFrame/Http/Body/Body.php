<?php

namespace LiteFrame\Http\Body;

use Exception;
use LiteFrame\Exceptions\FormatException;

abstract class Body {

    public abstract function read(): string;
    public abstract function write(string $content): void;
    public abstract function end(): void;

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


    public function writeJson(array $json): void {
        $str = json_encode($json);
        if ($str === false) {
            throw new Exception("Unable to encode JSON!.");
        }
        $this->write($str);
    }


}

?>