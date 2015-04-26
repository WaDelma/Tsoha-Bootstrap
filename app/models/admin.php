<?php

/**
 * Description of admin
 *
 * @author delma
 */
class Admin extends BaseModel {

    public $email, $hash, $name, $id, $super;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_email');
    }

    public function validate_name() {
        $length = strlen($this->name);
        if ($length == 0) {
            return array('Empty name.');
        }
        if ($length > 30) {
            return array('Too long name: ' . $length . ' > 30.');
        }
        return array();
    }

    public function validate_email() {
        $err = array();
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $err[] = 'Email is not valid.';
        }
        $length = strlen($this->email);
        if ($length > 30) {
            $err[] = 'Too long email: ' . $length . ' > 30.';
        }
        return $err;
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
            parent::add($a, $row, 'super');
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
            parent::add($a, $row, 'super');
            $admin = new Admin($a);
        }
        return $admin;
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM Admin ORDER BY id ASC;");
        $query->execute();
        $rows = $query->fetchAll();
        $admins = array();
        foreach ($rows as $row) {
            $a = array();
            parent::add($a, $row, 'id');
            parent::add($a, $row, 'name');
            parent::add($a, $row, 'hash');
            parent::add($a, $row, 'email');
            parent::add($a, $row, 'super');
            $admins[] = new Admin($a);
        }
        return $admins;
    }

    public function hasControl($board) {
        if ($this->super) {
            return true;
        }
        $query = DB::connection()->prepare('SELECT COUNT (*) FROM AdminBoard WHERE boardId=:boardId AND adminId=:adminId;');
        $query->execute(array('boardId' => $board->id, 'adminId' => $this->id));
        $row = $query->fetch();
        return 1 == (int) $row[0];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Admin SET hash=:hash, email=:email, super=:super WHERE id=:id;');
        $query->execute(array('id' => $this->id, 'hash' => $this->hash, 'email' => $this->email, 'super' => $this->super ? 'true' : 'false'));
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Admin (name, email, hash, super) VALUES (:name, :email, :hash, :super) RETURNING id;');
        $query->execute(array('name' => $this->name,
            'email' => $this->email,
            'hash' => $this->hash,
            'super' => $this->super ? 'true' : 'false'));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM AdminBoard WHERE adminid=:id;');
        $query->execute(array($this->id));

        $query = DB::connection()->prepare('DELETE FROM Admin WHERE id=:id;');
        $query->execute(array($this->id));
    }

    public function checkPassword($password) {
        $hash = self::hash($this->name, $password);
        return $hash === $this->hash;
    }

    public function setPassword($password) {
        $this->hash = self::hash($this->name, $password);
    }

    public static function hash($saltsource, $password) {
        $email = crypt($saltsource, '$2a$07$qwertyuiopasdfghjklzxc');
        $salt = substr($email, 61 - 23, 61);
        return crypt($password, '$2a$07$' . $salt);
    }

}
