<?php

/**
 * Description of board_model
 *
 * @author delma
 */
class Board extends BaseModel {

    public $id, $name, $description;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM Board");
        $query->execute();
        $rows = $query->fetchAll();
        $boards = array();
        foreach ($rows as $row) {
            $board = array();
            parent::add($board, $row, 'id');
            parent::add($board, $row, 'name');
            parent::add($board, $row, 'description');
            $boards[] = new Board($board);
        }
        return $boards;
    }

    public static function find($id) {
        $query = DB::connection()->prepare("SELECT * FROM Board WHERE id = :id LIMIT 1");
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $board = null;
        if ($row) {
            $b = array();
            parent::add($b, $row, 'id');
            parent::add($b, $row, 'name');
            parent::add($b, $row, 'description');
            $board = new Board($b);
        }
        return $board;
    }

    public static function findByName($name) {
        $query = DB::connection()->prepare("SELECT * FROM Board WHERE name = :name LIMIT 1");
        $query->execute(array('name' => $name));
        $row = $query->fetch();
        $board = null;
        if ($row) {
            $b = array();
            parent::add($b, $row, 'id');
            parent::add($b, $row, 'name');
            parent::add($b, $row, 'description');
            $board = new Board($b);
        }
        return $board;
    }

}
