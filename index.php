<?php

declare(strict_types=1);
mb_internal_encoding('UTF-8');

function autoloadFunction(string $class) : void
{
    if (preg_match("/Presenter$/", $class))
        require("Presenter/" . $class . ".php");
    else
        require("Model/" . $class . ".php");
}

spl_autoload_register("autoloadFunction");

Db::connect("127.0.0.1", "trackmania_db", "root", "");

$router = new RouterPresenter();
$router->process(array($_SERVER['REQUEST_URI']));