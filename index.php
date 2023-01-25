<?php
session_start();
 require_once __DIR__ . '/vendor/autoload.php';
 $config = include (__DIR__ . '/config/config.php');
 define("ROOT_PATH", __DIR__);

 //lсделаю логировать в будущем
try{
    //Initial request
    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    //DI контейнер
    $container = new Pimple\Container();
    $container['config'] = $config;
    //Request data
    $container['request'] = $request;
    //Sessions
    $container['session'] = $container->factory(function ($c) {
        $session = new \App\Classes\Session();
        return $session;
    });
    //БД
    $container['db'] = function () use(&$config) {
        $host = $config['db']['host'] ?? 'localhost';
        $database = $config['db']['database'] ?? 'postgres';
        $user = $config['db']['user'] ?? 'postgres';
        $password = $config['db']['password'] ?? '';
        $port = $config['db']['port'] ?? '5432';

        $dsn = "pgsql:host=$host;port=$port;dbname=$database;user=$user;password=$password";

        $dbh = new PDO($dsn);
        if($dbh){
            $log =  "Connected to the <strong>$database</strong> database successfully!";
        }
        return $dbh;
    };


    //Инициализируем вход
    (new App\Router($container))->init();
}catch(\App\Exceptions\LkException $e){
    echo $e->getMessage();
}catch (PDOException $e){
     echo $e->getMessage();
}catch(\Exception $e) {
    echo $e->getMessage();
}
 
