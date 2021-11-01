<?php

use LiteFrame\Exceptions\FormatException;
use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Exceptions\WriteFailureException;
use LiteFrame\Exceptions\WriteOnlyException;
use LiteFrame\Http\Body\BufferedBody;
use LiteFrame\Http\Body\ReadableBody;
use LiteFrame\Http\Body\WritableBody;

test('BufferedBody', function () {

    $buffer = "";
    $ended = false;

    $body = new BufferedBody(function($content) use (&$buffer) {
        $buffer .= $content;
    }, function() use(&$ended) {
        $ended = true;
    });

    $body->write("{");
    $body->write('"number": 10');
    $body->write("}");

    expect($buffer)->toBeEmpty();
    expect($body->readJson())->toEqual([
        "number" => 10
    ]);
    $body->end();

    expect($buffer)->toEqual('{"number": 10}');

    expect(function() use ($body) {
        $body->write("This should NOT work");
    })->toThrow(WriteFailureException::class);

});
