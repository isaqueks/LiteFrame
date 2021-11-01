<?php

namespace LiteFrame\Core;

use LiteFrame\Http\Request;

class Server {

    private static ?Request $currRequest = null;

    public static function currentRequest(): Request {

        if (Server::$currRequest === null) {
            $headers = getallheaders() ?? [];
            $url = $_SERVER["REQUEST_URI"];
            $method = $_SERVER["REQUEST_METHOD"];
            $body = file_get_contents("php://input") ?? "";
            Server::$currRequest = new Request($url, $method, $body, $headers);
        }

        return Server::$currRequest;
    }


}

?>