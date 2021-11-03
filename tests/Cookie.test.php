<?php

use LiteFrame\Http\Cookie;

test("Cookie", function() {

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
    $cook->setAttribute("Will-BeRemoved", "true");
    $cook->removeAttribute("Will-BeRemoved");
    expect($cook->attribute("Will-BeRemoved"))->toBeNull();

    expect($cook->getAllAttributes())->toEqual([
        "Max-Age" => "1000",
        "HttpOnly" => null
    ]);

    expect($cook->hasAttribute("HttpOnly"))->toBeTrue();
    expect($cook->hasAttribute("Inexistent"))->toBeFalse();
    expect($cook->hasAttribute("Will-BeRemoved"))->toBeFalse();

    expect($cook->toHeaderString())->toEqual("testCookie=new+value; Max-Age=1000; HttpOnly;");

});

?>