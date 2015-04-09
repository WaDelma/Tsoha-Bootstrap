<?php

/**
 * Description of front_page_controller
 *
 * @author delma
 */
class FrontPageController extends BaseController {

    public static function frontpage() {
        $boards = Board::all();
        $users = User::count();
        $posts = Post::count();
        $threads = Thread::count();
        $admin = parent::get_user_logged_in();
        View::make('home.html', array('boards' => $boards, 'users' => $users, 'posts' => $posts, 'threads' => $threads, 'admin' => $admin));
    }

}
