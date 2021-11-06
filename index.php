<?php

use LiteFrame\Core\Server;

require 'vendor/autoload.php';

$req = Server::currentRequest();
$res = Server::currentResponse();

$res->view("view.php", [
    "name" => $req->url()->queryParam("name")
]);
$res->end();

?>