<?php

require __DIR__.'/../../vendor/autoload.php';

session_save_path(
    __DIR__.'/../resources/sessions/'
);

session_start();

new \Kioku\App();
