<?php

use LiteFrame\Core\URL;

test('URL', function () {

    $href = "/dir/index.html?param1=test&param2=this%20is%20a%20test";

    $url = new URL($href);

    expect($url->href())->toEqual($href);
    expect($url->path())->toEqual("/dir/index.html");
    expect($url->queryString())->toEqual("param1=test&param2=this%20is%20a%20test");
    
    expect($url->queryParam("param1"))->toEqual("test");
    expect($url->queryParam("param2"))->toEqual("this is a test");
    expect($url->queryParam("inexistent"))->toBeNull();

});
