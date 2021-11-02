<?php

use LiteFrame\Http\HeaderManager;

test('HeaderManager', function () {

    $manager = new HeaderManager([
        "User-Agent" => "Test UA",
        "X-Forwarded-For" => "0.0.0.0"
    ]);

    expect($manager->get("User-Agent"))->toEqual("Test UA");
    expect($manager->get("X-Forwarded-For"))->toEqual("0.0.0.0");

    expect($manager->getRaw("User-Agent")->value)->toEqual("Test UA");
    expect($manager->getRaw("X-Forwarded-For")->value)->toEqual("0.0.0.0");

    expect(sizeof($manager->getAllMatching("User-Agent")))->toEqual(1);
    expect(sizeof($manager->getAllRaw()))->toEqual(2);

    $manager->set("user-agent", "newUA");
    expect($manager->get("User-Agent"))->toEqual("newUA");
    expect($manager->getRaw("User-Agent")->value)->toEqual("newUA");
    expect(sizeof($manager->getAllMatching("user-agent")))->toEqual(1);

    $manager->set("Set-Cookie", "cookie1=value1", false);
    $manager->set("Set-Cookie", "cookie2=value2", false);

    expect($manager->get("SET-COOKIE"))->toEqual("cookie1=value1");

    $setCookies = $manager->getAllMatching("set-cookie");
    expect(sizeof($setCookies))->toEqual(2);

    expect($setCookies[0]->is("Set-Cookie"))->toBeTrue();
    expect($setCookies[1]->is("set-cookie"))->toBeTrue();

    expect($setCookies[0]->value)->toEqual("cookie1=value1");
    expect($setCookies[1]->value)->toEqual("cookie2=value2");
    
    $all = $manager->getAllRaw();

    expect(sizeof($all))->toEqual(4);

    var_dump($all);

    // when set is called within replace = false,
    // the old is removed and a new is pushed to the array
    expect($all[1]->name())->toEqual("USER-AGENT");
    expect($all[0]->name())->toEqual("X-FORWARDED-FOR");


});
