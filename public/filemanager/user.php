<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

function getUser()
{
    require '../../bootstrap/autoload.php';
    $app = require_once '../../bootstrap/app.php';
    $config = require_once '../../bootstrap/cache/config.php';
    
    echo $_COOKIE[$config["session"]["cookie"]] . "<br>";
    echo $config["app"]["key"] . "<br>";
    $encrypter = new Illuminate\Encryption\Encrypter($config["app"]["key"], $config["app"]["cipher"]);
    
    //$app = new Illuminate\Foundation\Application(
    //    realpath(__DIR__.'/../')
    //);
    //
    //$id = $app['encrypter']->decrypt($_COOKIE[$app['config']['session.cookie']]);

}

getUser();
?>