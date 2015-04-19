<?php

/**
 * Description of admin_board
 *
 * @author delma
 */
class AdminBoard extends BaseModel {

    public $adminBoard;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM AdminBoard;");
        $query->execute();
        $rows = $query->fetchAll();
        $admin = array();
        foreach ($rows as $row) {
            $admin[$row['adminid']][$row['boardid']] = true;
        }
        return $admin;
    }

    public function save() {
        $query = DB::connection()->prepare("DELETE FROM AdminBoard;");
        $query->execute();
        foreach ($this->adminBoard as $ad => $bo) {
            foreach ($bo as $b => $v) {
                $query = DB::connection()->prepare("INSERT INTO AdminBoard (boardId, adminId) VALUES (:boardid, :adminid);");
                $query->execute(array('boardid' => $b, 'adminid' => $ad));
            }
        }
    }

}
