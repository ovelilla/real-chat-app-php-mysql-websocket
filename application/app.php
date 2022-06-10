<?php 
require '../vendor/autoload.php';
require 'scripts/functions.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
