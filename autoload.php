<?php
namespace TreeBuilder;

function load($namespace) {
    $splitPath = explode('\\', $namespace);
    $splitPathCount = count($splitPath);

    if (($splitPathCount >= 2) && ($splitPath[0] == __NAMESPACE__)) {
        unset($splitPath[0]);
        $fullPath = __DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $splitPath) . '.php';
        /** @noinspection PhpIncludeInspection */
        return include_once($fullPath);
    }
    return false;
}

spl_autoload_register(__NAMESPACE__ . '\load', false, true);