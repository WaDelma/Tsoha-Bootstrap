<?php

/**
 * Description of board_controller
 *
 * @author delma
 */
class AdminController extends BaseController {

    public static function page() {
        View::make('admin.html');
    }

    public static function logout() {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            if ($_SESSION['user']) {
                $_SESSION['user'] = null;
                Redirect::back();
            } else {
                View::make('failed.html');
            }
        }
    }

    public static function login() {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $name = filter_input(INPUT_POST, 'name');
            $password = filter_input(INPUT_POST, 'password');
            $admin = Admin::findByName($name);
            if (self::checkHash($admin, $password)) {
                $_SESSION['user'] = $admin->id;
                Redirect::to('/');
            } else {
                View::make('failed.html');
            }
        }
    }

    public static function checkHash($admin, $password) {
        if ($admin) {
            $email = crypt($admin->email, '$2a$07$qwertyuiopasdfghjklzxc');
            $salt = substr($email, 61 - 23, 61);
            $hash = crypt($password, '$2a$07$' . $salt);
            return $hash === $admin->hash;
        }
        return false;
    }

    public static function ban() {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $admin = parent::get_user_logged_in();
            if ($admin) {
                $id = filter_input(INPUT_POST, 'id');
                $user = User::findById($id);
                self::banUser($user);
                Redirect::back();
            } else {
                View::make('failed.html');
            }
        }
    }

    public static function banUser($user) {
        if ($user) {
            $posts = Post::findByUser($user->id);
            foreach ($posts as $post) {
                $post->delete();
            }
            self::deleteEmptyThreads();
            $ban = new Banned(array('ip' => $user->ip));
            $ban->save();
            $user->delete();
        }
    }

    public static function deleteEmptyThreads() {
        $threads = Thread::all();
        foreach ($threads as $thread) {
            $posts = Post::findForThread($thread->id);
            if (count($posts) === 0) {
                $thread->delete();
            }
        }
    }

}
