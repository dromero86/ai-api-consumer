<?php 

require "vendor/autoload.php"; 

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$_ENV["BASEPATH"] = __DIR__;

Tero\App::Run();