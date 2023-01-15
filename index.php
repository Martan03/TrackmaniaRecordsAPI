<?php

declare(strict_types=1);
mb_internal_encoding('UTF-8');

function autoloadFunction(string $class) : void
{
    require("Model/" . $class . ".php");
}

spl_autoload_register("autoloadFunction");