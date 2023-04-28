<?php 

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
require 'funciones.php';
require 'database.php';

debuguear($_ENV);

// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setDB($db);