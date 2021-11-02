<?php

use LiteFrame\Http\Cookie;

test("Cookies", function() {

    $cook = new Cookie("testCookie", "test value", [
        "Max-Age" => "idk"
    ]);

    expect($cook->name())->toEqual("testCookie");
    expect($cook->value())->toEqual("test value");

    expect($cook->attribute("Max-Age"))->toEqual("idk");
    expect($cook->attribute("Inexistent"))->toBeNull();

    $cook->setValue("new value");
    expect($cook->value())->toEqual("new value");

    $cook->setAttribute("Max-Age", "1000");
    $cook->setAttribute("HttpOnly");

    expect($cook->getAllAttributes())->toEqual([
        "Max-Age" => "1000",
        "HttpOnly" => null
    ]);

    expect($cook->toHeaderString())->toEqual("testCookie=new+value; Max-Age=1000; HttpOnly;");

});

?>