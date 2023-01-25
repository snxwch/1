<?php


namespace App\Controllers;


use App\Models\User;

class AccountController extends BaseController
{
   public function index()
   {
       $config = $this->getConfig();

       $user = $this->di['session']->get('user') ?? null;
       if(!$user) {
           return redirect($config['url'] .'/?c=login');
       }
       //Обновление сессионные данных
       // Это КОСТЫЛИ
       $objUser = new User($this->di['db']);
       if($objUser->IsRefreshAdmin($user)) {
           $this->di['session']->forget('user');
           return redirect($config['url'] .'/?c=login');
       }
       if($objUser->IsRefreshStatus($user)) {
           return redirect($config['url'] .'/?c=account');
       }

       $params = [
           'config' => $config,
           'user'    => $user,
           //треш
           'country' => dbhelper('country', $this->di['db'], $user->country_id),
           'region'  => dbhelper('region', $this->di['db'], $user->region_id),
           'city'    => dbhelper('city', $this->di['db'], $user->city_id),
       ];
       return $this->view('account', $params);
   }
   public function logout()
   {
       $config = $this->getConfig();
       $this->di['session']->forget('user');
       return redirect($config['url'] .'/?c=login');
   }

}