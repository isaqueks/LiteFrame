<?php

namespace LiteFrame\Core;

use LiteFrame\Http\Body\BufferedBody;
use LiteFrame\Http\Body\WritableBody;
use LiteFrame\Http\Request;
use LiteFrame\Http\Response;

class Server {

    private static ?Request $currRequest = null;
    private static ?Response $currResponse = null;

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

    public static function currentResponse(): Response {

        if (Server::$currResponse === null) {

            $body = new BufferedBody(function ($buff, $res) {
                echo $buff;
            }, function() {

            });

            $setHeader = function($name, $value) {
                header("$name: $value", true);
            };

            $setStatus = function($status) {
                http_response_code($status);
            };

            $headers = [
                "Connection" => "close",
                "Content-Type" => "text/html"
            ];

            Server::$currResponse = new Response($body, $setHeader, $setStatus, $headers);
        }

        return Server::$currResponse;

    }


}

?>