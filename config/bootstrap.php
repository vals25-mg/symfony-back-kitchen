<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Charge les variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(__DIR__).'/.env');

// Si tu veux charger les variables d'env à partir de Docker, tu peux les forcer ici
$_SERVER['APP_ENV'] = getenv('APP_ENV') ?: 'prod';
$_SERVER['APP_DEBUG'] = getenv('APP_DEBUG') ?: '0';
$_SERVER['APP_SECRET'] = getenv('APP_SECRET') ?: 'default_secret';
$_SERVER['DATABASE_URL'] = getenv('DATABASE_URL') ?: 'default_database_url';
