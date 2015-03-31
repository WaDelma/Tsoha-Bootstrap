<?php

/**
 * Description of thread
 *
 * @author delma
 */
class Thread extends BaseModel {

    public $id, $boardid;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM Thread");
        $query->execute();
        $rows = $query->fetchAll();
        $threads = array();
        foreach ($rows as $row) {
            $thread = array();
            parent::add($thread, $row, 'id');
            parent::add($thread, $row, 'boardid');
            $threads[] = new Thread($thread);
        }
        return $threads;
    }

    public static function find($id) {
        $query = DB::connection()->prepare("SELECT * FROM Thread WHERE id = :id LIMIT 1");
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $thread = null;
        if ($row) {
            $t = array();
            parent::add($t, $row, 'id');
            parent::add($t, $row, 'boardid');
            $thread = new Thread($t);
        }
        return $thread;
    }

    public static function findForBoard($board) {
        $query = DB::connection()->prepare("SELECT t.* FROM Thread AS t JOIN board as b ON b.id = t.boardId WHERE b.id = :boardid ORDER BY t.id DESC");
        $query->execute(array('boardid' => $board));
        $rows = $query->fetchAll();
        $threads = array();
        foreach ($rows as $row) {
            $thread = array();
            parent::add($thread, $row, 'id');
            parent::add($thread, $row, 'boardid');
            $threads[] = new Thread($thread);
        }
        return $threads;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Thread (boardid) VALUES (:boardid) RETURNING id;');
        $query->execute(array('boardid' => $this->boardid));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public static function count() {
        $query = DB::connection()->prepare('SELECT COUNT(*) FROM Thread;');
        $query->execute();
        $row = $query->fetch();
        return $row[0];
    }

}
