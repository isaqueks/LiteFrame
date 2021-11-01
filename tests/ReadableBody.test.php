<?php

use LiteFrame\Exceptions\FormatException;
use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Http\Body\ReadableBody;

test('ReadableBody', function () {
    $body = new ReadableBody("Hello World");

    expect($body->read())->toEqual("Hello World");

    expect(function() use ($body) {
        $body->write("I shouldn't be able to write.");
    })->toThrow(ReadOnlyException::class);

    expect(function() use ($body) {
        //! Invalid JSON, should throw
        $body->readJson();
    })->toThrow(FormatException::class);

    $body = new ReadableBody('{ "age": 17, "name": "Isaque" }');
    expect($body->readJson())->toEqual([
        "age" => 17,
        "name" => "Isaque"
    ]);

    $body = new ReadableBody("param1=Test&param2=It%20works");

    expect($body->readURLEncoded())->toEqual([
        "param1" => "Test",
        "param2" => "It works"
    ]);

});
