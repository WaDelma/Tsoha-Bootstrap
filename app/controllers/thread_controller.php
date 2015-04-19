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
            $t = new Thread(array('id' => $thread));
            $t->delete();
            Redirect::back(array('errors' => $errors));
        }
    }

    public static function thread($board, $thread) {
        $b = Board::findByName($board);
        if (!$b) {
            Redirect::to('/');
        }
        $boards = Board::all();
        $posts = Post::findForThread($thread);
        if (count($posts) == 0) {
            Redirect::to('/' . $board);
        }
        $admin = parent::get_user_logged_in();
        $control = $admin && $admin->hasControl($b);
        View::make('thread.html', array('boards' => $boards, 'board' => $b, 'thread' => $thread, 'messages' => $posts, 'admin' => $admin, 'control' => $control));
    }

}
