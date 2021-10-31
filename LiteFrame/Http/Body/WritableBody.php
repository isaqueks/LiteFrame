<?php

namespace LiteFrame\Http\Body;

use Exception;
use LiteFrame\Exceptions\FormatException;
use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Exceptions\WriteOnlyException;

class WritableBody extends Body {

    protected $writeFn;

    function __construct($writeFn) {
        if (!is_callable($writeFn)) {
            throw new Exception("writeFn must be a callable function!");
        }
        $this->writeFn = $writeFn;
    }

    private function throwWriteOnly() {
        throw new WriteOnlyException("Cannot read of write-only body!", 1);
    }

    public function read(): string {
        $this->throwWriteOnly();
    }

    public function readJson(): array {
        $this->throwWriteOnly();
    }

    public function readURLEncoded(): array {
        $this->throwWriteOnly();
    }

    public function readFormData(): array {
        $this->throwWriteOnly();
    }


    public function write(string $content): void {
        call_user_func($this->writeFn, $content);
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