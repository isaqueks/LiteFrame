<?php

use LiteFrame\Http\Cookie;
use LiteFrame\Http\CookieManager;

test("CookieManager", function() {

    $manager = new CookieManager([
        "TestCookie" => "Test Value"
    ]);

    expect(sizeof($manager->allCookies()))->toEqual(1);
    expect($manager->get("TestCookie")?->value())->toEqual("Test Value");
    expect($manager->getCookieValue("TestCookie"))->toEqual("Test Value");

    $manager->set("TestCookie", "New value", [ "HttpOnly" => null ]);
    expect($manager->get("TestCookie")->value())->toEqual("New value");
    expect($manager->get("TestCookie")->hasAttribute("HttpOnly"))->toBeTrue();

    $manager->get("TestCookie")->setValue("Newest value");
    expect($manager->get("TestCookie")->value())->toEqual("Newest value");
    expect($manager->getCookieValue("TestCookie"))->toEqual("Newest value");

    $manager->set("userId", "1");
    expect($manager->getCookieValue("userId"))->toEqual("1");
    // Check if that cookie stills there
    expect($manager->getCookieValue("TestCookie"))->toEqual("Newest value");

    $manager->remove("TestCookie");
    expect($manager->getCookieValue("TestCookie"))->toBeNull();
    expect($manager->get("TestCookie"))->toBeNull();

    $manager->setRaw(new Cookie("TestCookie", "Value 2.0"));
    expect($manager->getCookieValue("TestCookie"))->toEqual("Value 2.0");

    expect($manager->toHeadersString())->toEqual([
        "userId=1",
        "TestCookie=Value+2.0"
    ]);



});

?>