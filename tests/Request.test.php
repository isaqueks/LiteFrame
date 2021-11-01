<?php

use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Http\Request;

test('Request', function () {

    $req = new Request("/index.php", "Post", "user=Isaque&lang=en", [
        "User-Agent" => "TestUA",
        "Cookie" => "userId=1;"
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

    $req->setcookie("testCookie", "testValue");
    expect($req->cookie("testCookie"))->toEqual("testValue");

    $req->setHeader("Test-Header", "This is a test.");
    expect($req->header("test-header"))->toEqual("This is a test.");

    $req->lockHeaders();

    expect(function() use($req) {
        $req->setHeader("Read-Only", "true");
    })->toThrow(ReadOnlyException::class);

    expect(function() use($req) {
        $req->setCookie("Read-Only", "true");
    })->toThrow(ReadOnlyException::class);

});
