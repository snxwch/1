<?php
namespace App\Models;


class User extends Model
{

   protected $table = "users";
   public function IsRefreshStatus($user): bool
   {
      $id = (int)$user->id;
      $status = $user->status;
      $sql = "SELECT status FROM users WHERE id = $id";
      $query = $this->db->query($sql);
      $r = $query->fetchObject();

       if((bool)$r->status !== $status) {
           $user->status = (bool)$r->status;
           return true;
       }

       return false;
   }
   public function IsRefreshAdmin($user): bool
   {
       $id = (int)$user->id;
       $isAdmin = (bool)$user->isadmin;

       $sql = "SELECT isadmin FROM users WHERE id = $id";
       $query = $this->db->query($sql);
       $r = $query->fetchObject();

       if((bool)$r->isadmin !== $isAdmin) {
           return true;
       }

       return false;
   }

    /**
     * @return array
     */
   public function getUsersForFalseStatus()
   {
       $sql = "SELECT * FROM users WHERE status = false";
       $query = $this->db->query($sql);
      return $query->fetchAll();

   }
   public function login($email, $password)
   {
       $sql = "SELECT * FROM users WHERE email = '".$email."' 
               AND password = '".$password."'";
       $query = $this->db->query($sql);
       $result = $query->fetchObject();

       return $result ?? null;
   }

   public function status(int $id, bool $status) //: void - for php 7.1 or higher
   {
       $sql = "UPDATE users SET status=? WHERE id=?";
       $stmt = $this->db->prepare($sql);
       $stmt->execute([$status, $id]);
   }


}