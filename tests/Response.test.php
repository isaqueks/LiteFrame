<?php

use LiteFrame\Exceptions\ReadOnlyException;
use LiteFrame\Http\Body\BufferedBody;
use LiteFrame\Http\Body\WritableBody;
use LiteFrame\Http\Response;

test("Response", function() {

    $buffer = "";
    $ended = false;

    $body = new BufferedBody(function($data) use(&$buffer) {
        $buffer .= $data;
    }, function() use (&$ended) {
        $ended = true;
    });

    $head = "";
    $statusCode = 200;

    $res = new Response($body, function ($name, $content) use (&$head) {

        $head .= "$name: $content\n";

    }, function ($status) use (&$statusCode) {
        $statusCode = $status;
    }, [
        "User-Agent" => "FakeUA",
        "Set-Cookie" => "userId=6"
    ]);

    expect($res->header("user-agent"))->toEqual("FakeUA");

    $resJSON = [
        "user" => [
            "name" => "Isaque",
            "GitHub" => "isaqueks"
        ]
    ];

    $res->body()->writeJson($resJSON);

    $res->setHeader("Content-Type", "text/json");
    expect($res->header("CONTENT-TYPE"))->toEqual("text/json");

    expect($res->cookie("userId"))->toEqual("6");

    $res->setCookie("userId", "1");
    expect($res->cookie("userId"))->toEqual("1");

    $res->setCookie("userId", "2");
    expect($res->cookie("userId"))->toEqual("2");

    // Sorry, I can't think other name
    $res->setCookie("save", "true");

    expect($res->cookie("save"))->toEqual("true");

    expect($head)->toBeEmpty();

    $allHeaders = $res->allHeaders();
    $setCookieCount = 0;

    foreach ($allHeaders as $header) {
        if ($header->is("Set-Cookie")) {
            $setCookieCount += 1;
        }
    }

    expect($setCookieCount)->toEqual(2);

    $res->setStatusCode(404);
    expect($res->statusCode())->toEqual(404);

    // Response is buffered, so real
    // statusCode should change when
    // $res->end is called
    expect($statusCode)->toEqual(200);

    $res->end();
    expect($statusCode)->toEqual(404);
    expect($buffer)->toEqual(json_encode($resJSON));
    expect($res->body()->read())->toEqual($buffer);
    expect($ended)->toBeTrue();

    expect($head)->toEqual(
        "User-Agent: FakeUA\n" .
        "Content-Type: text/json\n" .
        "Set-Cookie: userId=2\n" . 
        "Set-Cookie: save=true\n"
    );

    expect(function () use($res) {

        $res->setHeader("Should Not", "Work");

    })->toThrow(ReadOnlyException::class);


});

?>