<?php 
 namespace App\Controllers;

 use App\Exceptions\LkException;

 class BaseController
 {
     protected $di;
     public function __construct($di)
     {
         //Уточняем что пришел именно нужный нам объект
         if($di instanceof \Pimple\Container) {
             $this->di = $di;
         }else{
             abort404();
         }

     }
     /**
      * @return array
      */
     protected function getConfig(): array
     {
         if($this->di) {
             return $this->di['config'];
         }
         $config = include(ROOT_PATH . '/config/config.php');
         return $config;
     }

     /**
      * Генерация views и параметров
      * @param $file_name
      * @param array $params
      * @throws LkException
      */
     protected function view($file_name, array $params = [])
     {
         $ext = ".phtml";
         $path = __DIR__ . "/../Views/";
         if(!file_exists($path . $file_name.$ext)) {
             throw new LKException( "View file not found" );
         }

         ob_start();
         if($params) {
             extract($params);
         }
         $template = include($path . $file_name . $ext);
         echo $template;
         ob_get_contents();
         ob_end_flush();
     }
 } 