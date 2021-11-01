<?php

namespace LiteFrame\Http\Body;


use LiteFrame\Exceptions\WriteFailureException;
use LiteFrame\Exceptions\WriteOnlyException;
use TypeError;

class BufferedBody extends WritableBody {

    protected string $buffer = "";

    public function read(): string {
        return $this->buffer;
    }

    public function write(string $content): void {
        if ($this->ended) {
            throw new WriteFailureException("Cannot write to body after being closed!");
        }
        $this->buffer .= $content;
    }

    public function end(): void {
        call_user_func($this->writeFn, $this->buffer, $this);
        call_user_func($this->endFn, $this);
        $this->ended = true;
    }

}

?>