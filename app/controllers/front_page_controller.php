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
        View::make('home.html', array('boards' => $boards, 'users' => $users, 'posts' => $posts, 'threads' => $threads));
    }

}
