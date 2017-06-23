<?php

$route->add('GET', '/', '', [], function() {
    dd('hello');
});

$route->add('GET', '/{string}-{integer}', '', [], function (string $name, int $id) {
    dd($name, $id);
});