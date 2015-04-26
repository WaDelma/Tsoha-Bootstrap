<?php

/**
 * Description of post
 *
 * @author delma
 */
class Post extends BaseModel {

    public $id, $threadid, $userid, $content;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_content');
    }

    public function validate_content() {
        $length = strlen($this->content);
        if ($length == 0) {
            return array('Empty message.');
        }
        if ($length > 10000) {
            return array('Too long message: ' . $length . ' > 10000.');
        }
        return array();
    }

    public static function findForThread($thread, $page, $pagesize) {
        $query = DB::connection()->prepare("SELECT * FROM Post WHERE threadid = :threadid ORDER BY id ASC LIMIT :limit OFFSET :offset");
        $query->execute(array('threadid' => $thread->id, 'limit' => $pagesize, 'offset' => $pagesize * ($page - 1)));
        $rows = $query->fetchAll();
        $posts = array();
        foreach ($rows as $row) {
            $post = array();
            parent::add($post, $row, 'id');
            parent::add($post, $row, 'threadid');
            parent::add($post, $row, 'userid');
            parent::add($post, $row, 'content');
            $posts[] = new Post($post);
        }
        return $posts;
    }

    public static function findByUser($id) {
        $query = DB::connection()->prepare("SELECT * FROM Post WHERE userid = :userid ORDER BY id ASC");
        $query->execute(array('userid' => $id));
        $rows = $query->fetchAll();
        $posts = array();
        foreach ($rows as $row) {
            $post = array();
            parent::add($post, $row, 'id');
            parent::add($post, $row, 'threadid');
            parent::add($post, $row, 'userid');
            parent::add($post, $row, 'content');
            $posts[] = new Post($post);
        }
        return $posts;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Post (threadid, userid, content) VALUES (:threadid, :userid, :content) RETURNING id;');
        $query->execute(array('threadid' => $this->threadid,
            'userid' => $this->userid,
            'content' => $this->content));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Post WHERE id=:id;');
        $query->execute(array($this->id));
    }

    public static function count() {
        $query = DB::connection()->prepare('SELECT COUNT (*) FROM Post;');
        $query->execute();
        $row = $query->fetch();
        return $row[0];
    }

    public static function countForThread($thread) {
        $query = DB::connection()->prepare('SELECT COUNT (*) FROM Post WHERE threadid=:id;');
        $query->execute(array('id' => $thread->id));
        $row = $query->fetch();
        return $row[0];
    }

}
