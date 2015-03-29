<?php

/**
 * Description of banned
 *
 * @author delma
 */
class Banned extends BaseModel {

    public $ip;

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

}
