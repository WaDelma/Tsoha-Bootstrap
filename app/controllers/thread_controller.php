<?php

/**
 * Description of thread_controller
 *
 * @author delma
 */
class ThreadController extends BaseController {

    public static function send($board, $thread) {
        parent::checkBanned();
        $ip = $_SERVER['REMOTE_ADDR'];
        $user = User::findByIp($ip);
        if (!$user) {
            $user = User::create($ip);
        }
        $post = array('content' => filter_input(INPUT_POST, 'content'));
        $post['userid'] = $user->id;
        $post['threadid'] = $thread;
        $p = new Post($post);
        $errors = $p->errors();
        if (count($errors) == 0) {
            $p->save();
            Redirect::to('/' . $board . '/' . $thread);
        } else {
            Thread::deleteEmpty();
            Redirect::back(array('errors' => $errors));
        }
    }

    public static function delete($name, $threadid) {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        $board = Board::findByName($name);
        if ($admin && $admin->hasControl($board)) {
            $thread = Thread::find($threadid);
            $thread->delete();
            User::deleteEmpty();
            Redirect::back();
        } else {
            Redirect::back(array('errors' => array('No permission to delete board')));
        }
    }

    public static function thread($board, $threadid) {
        $data = array();
        $b = Board::findByName($board);
        if (!$b) {
            Redirect::to('/', array('errors' => array('Board doesn\'t exist')));
        }
        $data['board'] = $b;
        $thread = Thread::find($threadid);
        if (!$thread) {
            Redirect::to('/' . $board, array('errors' => array('Thread doesn\'t exist')));
        }
        $data['thread'] = $thread;
        $data['boards'] = Board::all();

        $page_size = 10;
        Pager::page($data, Post::countForThread($thread), $page_size);
        $data['messages'] = Post::findForThread($thread, $data['cur_page'], $page_size);
        $admin = parent::get_user_logged_in();
        if ($admin) {
            $data['control'] = $admin->hasControl($b);
        }
        $data['admin'] = $admin;
        View::make('thread.html', $data);
    }

}
