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

    public static function count() {
        $query = DB::connection()->prepare('SELECT COUNT(*) FROM Useri;');
        $query->execute();
        $row = $query->fetch();
        return $row[0];
    }

}