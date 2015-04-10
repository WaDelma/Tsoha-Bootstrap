<?php

/**
 * Description of user
 *
 * @author delma
 */
class User extends BaseModel {

    public $id, $ip;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM Useri;");
        $query->execute();
        $rows = $query->fetchAll();
        $users = array();
        foreach ($rows as $row) {
            $u = array();
            parent::add($u, $row, 'id');
            parent::add($u, $row, 'ip');
            $users[] = new User($u);
        }
        return $users;
    }

    public static function findById($id) {
        $query = DB::connection()->prepare("SELECT * FROM Useri WHERE id = :id LIMIT 1");
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $user = null;
        if ($row) {
            $u = array();
            parent::add($u, $row, 'id');
            parent::add($u, $row, 'ip');
            $user = new User($u);
        }
        return $user;
    }

    public static function findByIp($ip) {
        $query = DB::connection()->prepare("SELECT * FROM Useri WHERE ip = :ip LIMIT 1");
        $query->execute(array('ip' => $ip));
        $row = $query->fetch();
        $user = null;
        if ($row) {
            $u = array();
            parent::add($u, $row, 'id');
            parent::add($u, $row, 'ip');
            $user = new User($u);
        }
        return $user;
    }

    public static function create($ip) {
        $query = DB::connection()->prepare('INSERT INTO Useri (ip) VALUES (:ip) RETURNING id;');
        $query->execute(array('ip' => $ip));
        $row = $query->fetch();
        return new User(array('ip' => $ip, 'id' => $row['id']));
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Useri WHERE id=:id;');
        $query->execute(array($this->id));
    }

    public static function count() {
        $query = DB::connection()->prepare('SELECT COUNT (*) FROM Useri;');
        $query->execute();
        $row = $query->fetch();
        return $row[0];
    }

}
