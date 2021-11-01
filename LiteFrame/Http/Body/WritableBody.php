<?php

namespace LiteFrame\Http\Body;


use LiteFrame\Exceptions\WriteFailureException;
use LiteFrame\Exceptions\WriteOnlyException;
use TypeError;

class WritableBody extends Body {

    protected $writeFn;
    protected $endFn;
    protected bool $ended = false;

    function __construct(callable $writeFn, callable $endFn) {
        if (!is_callable($writeFn)) {
            throw new TypeError("writeFn must be a callable function!");
        }
        if (!is_callable($endFn)) {
            throw new TypeError("endFn must be a callable function!", 1);
        }
        $this->writeFn = $writeFn;
        $this->endFn = $endFn;
    }

    private function throwWriteOnly() {
        throw new WriteOnlyException("Cannot read of write-only body!", 1);
    }

    public function read(): string {
        $this->throwWriteOnly();
    }

    public function write(string $content): void {
        if ($this->ended) {
            throw new WriteFailureException("Cannot write to body after being closed!");
        }
        call_user_func($this->writeFn, $content, $this);
    }

    public function end(): void {
        $this->ended = true;
        call_user_func($this->endFn, $this);
    }

}

?>