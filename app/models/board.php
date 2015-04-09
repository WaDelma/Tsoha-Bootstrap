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
        $this->validators = array('validate_name', 'validate_description');
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

    public function validate_description() {
        $length = strlen($this->name);
        if ($length == 0) {
            return array('Empty description.');
        }
        if ($length > 30) {
            return array('Too long description: ' . $length . ' > 10000.');
        }
        return array();
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

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Board (name, description) VALUES (:name, :description) RETURNING id;');
        $query->execute(array('name' => $this->name,
            'description' => $this->description));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Post WHERE id=:id;');
        $query->execute(array($this->id));
    }

}
