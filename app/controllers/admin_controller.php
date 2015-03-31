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

    public static function login() {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $name = filter_input(INPUT_POST, 'name');
            $password = filter_input(INPUT_POST, 'password');
            $admin = Admin::findByName($name);
            if ($admin) {
                $salt = crypt($admin->email, '$2a$07$qwertyuiopasdfghjklzxc');
                $salt = substr($salt, 61 - 23, 61);
                $hash = crypt($password, '$2a$07$' . $salt);
//                Kint::dump($hash);
                if ($hash === $admin->hash) {
                    $_SESSION['user'] = $admin->id;
                    Redirect::to('/');
                    return;
                }
            }
            View::make('failed.html');
        }
    }

    public static function ban() {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $admin = parent::get_user_logged_in();
            if ($admin) {
                echo 'ban';
                return;
            }
            View::make('failed.html');
        }
    }

}
