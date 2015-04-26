<?php

/**
 * Description of thread
 *
 * @author delma
 */
class Thread extends BaseModel {

    public $id, $boardid, $title;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_title');
    }

    public function validate_title() {
        $length = strlen($this->title);
        if ($length == 0) {
            return array('Empty title.');
        }
        if ($length > 30) {
            return array('Too long title: ' . $length . ' > 30.');
        }
        return array();
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
            parent::add($thread, $row, 'title');
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
            parent::add($t, $row, 'title');
            $thread = new Thread($t);
        }
        return $thread;
    }

    public static function findForBoard($board, $page, $pagesize) {
        $query = DB::connection()->prepare('SELECT t.* FROM Thread AS t JOIN board as b ON b.id = t.boardId WHERE b.id = :boardid ORDER BY t.id DESC LIMIT :limit OFFSET :offset');
        $query->execute(array('boardid' => $board->id, 'limit' => $pagesize, 'offset' => $pagesize * ($page - 1)));
        $rows = $query->fetchAll();
        $threads = array();
        foreach ($rows as $row) {
            $thread = array();
            parent::add($thread, $row, 'id');
            parent::add($thread, $row, 'boardid');
            parent::add($thread, $row, 'title');
            $threads[] = new Thread($thread);
        }
        return $threads;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Thread (boardid, title) VALUES (:boardid, :title) RETURNING id;');
        $query->execute(array('boardid' => $this->boardid, 'title' => $this->title));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public static function deleteEmpty() {
        $query = DB::connection()->prepare('DELETE FROM Thread as t WHERE (SELECT COUNT(*) FROM Post WHERE threadid=t.id) = 0;');
        $query->execute();
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Post WHERE threadid=:id;');
        $query->execute(array($this->id));

        $query = DB::connection()->prepare('DELETE FROM Thread WHERE id=:id;');
        $query->execute(array($this->id));
    }

    public static function count() {
        $query = DB::connection()->prepare('SELECT COUNT (*) FROM Thread;');
        $query->execute();
        $row = $query->fetch();
        return $row[0];
    }

    public static function countForBoard($board) {
        $query = DB::connection()->prepare('SELECT COUNT (*) FROM Thread WHERE boardid=:id;');
        $query->execute(array('id' => $board->id));
        $row = $query->fetch();
        return $row[0];
    }

}
