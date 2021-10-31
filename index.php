<?php

use LiteFrame\Http\Request;

require 'vendor/autoload.php';

$req = Request::current();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request echo</title>
</head>
<body>
    
    <p>
        HTTP: <b><?= $req->method(); ?></b>&nbsp;<code><?= $req->url(); ?></code>
    </p>

    <hr>

    <h3>
        User-Agent:
    </h3>
    <code>
        <?= $req->header("User-Agent"); ?>
    </code>

    <hr>

    <h3>
        Cookies:
    </h3>
    <code>
        <?= $req->cookie("Test"); ?>
    </code>

    <hr>

    <h3>
        Body:
    </h3>

    <code>
        <?= $req->body()->readJSON()["user"]["age"]; ?> 
    </code>

</body>
</html>