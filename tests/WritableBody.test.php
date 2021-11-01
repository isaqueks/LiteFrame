<?php

use LiteFrame\Exceptions\FormatException;
use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Exceptions\WriteFailureException;
use LiteFrame\Exceptions\WriteOnlyException;
use LiteFrame\Http\Body\ReadableBody;
use LiteFrame\Http\Body\WritableBody;

test('WritableBody', function () {

    $buffer = "";
    $ended = false;

    $body = new WritableBody(function($content) use (&$buffer) {
        $buffer .= $content;
    }, function() use(&$ended) {
        $ended = true;
    });

    expect(function() use ($body) {
        $body->read("I shouldn't be able to read.");
    })->toThrow(WriteOnlyException::class);

    $body->write("Hello World");

    expect($buffer)->toEqual("Hello World");

    $buffer = "";

    $body->writeJson([
        "age" => 17,
        "name" => "Isaque"
    ]);

    expect($buffer)->toEqual(json_encode([
        "age" => 17,
        "name" => "Isaque"
    ]));

    $body->end();
    expect($ended)->toEqual(true);

    expect(function() use ($body) {
        $body->write("I shouldn't be able to write after being closed.");
    })->toThrow(WriteFailureException::class);

});
