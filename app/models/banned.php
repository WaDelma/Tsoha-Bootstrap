<?php

/**
 * Description of banned
 *
 * @author delma
 */
class Banned extends BaseModel {

    public $ip;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM Banned");
        $query->execute();
        $rows = $query->fetchAll();
        $banned = array();
        foreach ($rows as $row) {
            $banned[] = new Banned(array('ip' => $row['ip']));
        }
        return $banned;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Banned (ip) VALUES (:ip);');
        $query->execute(array('ip' => $this->ip));
    }

}
