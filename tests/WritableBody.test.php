<?php

use LiteFrame\Exceptions\FormatException;
use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Exceptions\WriteOnlyException;
use LiteFrame\Http\Body\ReadableBody;
use LiteFrame\Http\Body\WritableBody;

test('WritableBody', function () {

    $buffer = "";

    $body = new WritableBody(function($content) use (&$buffer) {
        $buffer .= $content;
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

});
