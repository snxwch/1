<?php
namespace App\Models;


class Model
{
   protected $db, $table;
   public function __construct(\PDO $db)
   {
       $this->db = $db;
   }

    /**
     * Вывод любых данных
     * @return array
     */
    public function getData()
    {

        $query = $this->db->query("SELECT * from {$this->table}");

        return $query->fetchAll();
    }
    public function getItem(int $id)
    {
        $query = $this->db->query("SELECT * from {$this->table} 
                                            WHERE id = $id");
        return $query->fetchObject();
    }

    /**
     *  Добавление любых данных
     * @param array $params
     * @return bool|object
     */
    public function insertData(array $params)
    {
        $values = [];
        $out = "";
        $i = 0;
        foreach($params as $key => $value) {
            $values[] = $value;
            $out .=  ($i) ? ",?" : "?"; $i++;
        }

        $sqlPrepare = "INSERT INTO users(".implode(",",
                array_keys($params)).")
                             VALUES($out)";

        $statement = $this->db->prepare($sqlPrepare);
        $status = $statement->execute($values);

        if($status) {
            $id = $this->db->lastInsertId('users_id_seq');
            return $this->getItem($id);
        }
        return $status;
    }
}