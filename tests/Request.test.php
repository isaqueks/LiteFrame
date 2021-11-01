<?php

use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Http\Request;

test('Request', function () {

    $req = new Request("/index.php", "Post", "user=Isaque&lang=en", [
        "userId" => "1"
    ], [
        "User-Agent" => "TestUA"
    ]);

    expect($req->href())->toEqual("/index.php");
    expect($req->method())->toEqual("POST");
    expect($req->body()->read())->toEqual("user=Isaque&lang=en");
    expect($req->body()->readURLEncoded())->toEqual([
        "user" => "Isaque",
        "lang" => "en"
    ]);
    expect($req->header("user-agent"))->toEqual("TestUA");
    expect($req->cookie("userId"))->toEqual("1");

    $req->setHeader("Test-Header", "This is a test.");
    expect($req->header("test-header"))->toEqual("This is a test.");

    $req->lockHeaders();

    expect(function() use($req) {
        $req->setHeader("Read-Only", "true");
    })->toThrow(ReadOnlyException::class);

});
