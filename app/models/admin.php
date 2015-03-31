<?php

/**
 * Description of admin
 *
 * @author delma
 */
class Admin extends BaseModel {

    public $email, $hash, $name, $id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function findByName($name) {
        $query = DB::connection()->prepare("SELECT * FROM Admin WHERE name = :name LIMIT 1");
        $query->execute(array('name' => $name));
        $row = $query->fetch();
        $admin = null;
        if ($row) {
            $a = array();
            parent::add($a, $row, 'id');
            parent::add($a, $row, 'name');
            parent::add($a, $row, 'hash');
            parent::add($a, $row, 'email');
            $admin = new Admin($a);
        }
        return $admin;
    }

    public static function find($id) {
        $query = DB::connection()->prepare("SELECT * FROM Admin WHERE id = :id LIMIT 1");
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $admin = null;
        if ($row) {
            $a = array();
            parent::add($a, $row, 'id');
            parent::add($a, $row, 'name');
            parent::add($a, $row, 'hash');
            parent::add($a, $row, 'email');
            $admin = new Admin($a);
        }
        return $admin;
    }

}
