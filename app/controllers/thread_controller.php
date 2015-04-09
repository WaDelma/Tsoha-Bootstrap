<?php

/**
 * Description of thread_controller
 *
 * @author delma
 */
class ThreadController extends BaseController {

    public static function send($board, $thread) {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
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
                Kint::dump($errors);
            }
        }
    }

    public static function thread($board, $thread) {
        $boards = Board::all();
        $posts = Post::findForThread($thread);
        $admin = parent::get_user_logged_in();
        View::make('thread.html', array('boards' => $boards, 'board' => $board, 'thread' => $thread, 'messages' => $posts, 'admin' => $admin));
    }

}
