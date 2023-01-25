<?php 
namespace App\Controllers;

use App\Models\User;
use Symfony\Component\HttpFoundation\Request;

 class RegisterController extends BaseController 
 {
     /**
      * Выводим форму регистрации
      * @throws \App\Exceptions\LkException
      */
    public function index()
    {
        $countries = (new \App\Models\Country($this->di['db']))
                    ->getData();
        $regions = (new \App\Models\Region($this->di['db']))
            ->getData();
        $citites = (new \App\Models\City($this->di['db']))
            ->getData();

        $params = [
            'countries' => $countries,
            'regions'   => $regions,
            'cities'    => $citites,
            'config'    => $this->getConfig()
        ];

        return $this->view('register',$params);
    }

     /**
      * Обработчик регистрации
      */
    public function register()
    {
      // dump($this->di['request']->server->get('REQUEST_METHOD'));
       $requestMethod =  $this->di['request']->server->get('REQUEST_METHOD');
       if($requestMethod != 'POST') {
           abort404();
       }
        $data = $this->di['request']->request->all();
       /**
         ТУТ ДОДЕЛАТЬ ВАЛИДАЦИЮ ДАННЫХ
        * или нет
        */

        if(isset($data['password_confirmation'])) {
            unset($data['password_confirmation']);
        }


       $data['password'] = _hpswd($data['password']);


       if(is_array($data)) {
         $db = $this->di['db'];
         $objUser = new User($db);
         if($objUser = $objUser->insertData($data)) {

             $this->di['session']->put('user', $objUser);
             return redirect($this->getConfig()['url'] .'/?c=login');
         }

         dump('Error insert database');


       }

    }
 }