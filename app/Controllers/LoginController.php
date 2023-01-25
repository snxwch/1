<?php
 namespace App\Controllers;

 use App\Models\User;

 class LoginController extends BaseController
 {
     public function index()
     {
        $config = $this->getConfig();
        $user = $this->di['session']->get('user') ?? null;
        if($user) {
            return redirect($config['url'] .'/?c=account');
        }

        $params = [
            'config' => $config
        ];
        return $this->view('login', $params);
     }
     public function login()
     {
         $requestMethod =  $this->di['request']->server->get('REQUEST_METHOD');
         if($requestMethod != 'POST') {
             abort404();
         }
         $data = $this->di['request']->request->all();
         $email = $data['email'];
         $password = _hpswd($data['password']);

         $objUser = new User($this->di['db']);
         if($objUser = $objUser->login($email, $password)) {

             $this->di['session']->put('user', $objUser);
             return redirect($this->getConfig()['url'] .'/?c=login');
         }

         dump('Error: login incorrect');


     }
 }