<?php

namespace App\Controllers;


use App\Models\User;

class AdminController extends BaseController
{
   public function index()
   {
       $config = $this->getConfig();

       $user = $this->di['session']->get('user') ?? null;
       if(!$user) {
           return redirect($config['url'] .'/?c=login');
       }
       //Обновление сессионные данные
       //Это КОСТЫЛИ
       $objUser = new User($this->di['db']);
       if($objUser->IsRefreshAdmin($user)) {
           $this->di['session']->forget('user');
           return redirect($config['url'] .'/?c=login');
       }

       if(!$user->isadmin) {
           return redirect($config['url'] .'/?c=account');
       }
       $users = $objUser->getUsersForFalseStatus();

       $params = [
           'config' => $config,
           'user'    => $user,
           'users'   => $users
       ];
       return $this->view('admin', $params);

   }
   public function changeStatus()
   {
      $user_id = (int)$this->di['request']->query->get('user_id');
      $status  = true;


      $objUser = new User($this->di['db']);
      $objUser->status($user_id, $status);


       $config = $this->getConfig();
      return redirect($config['url'] . '/?c=admin');
   }
}