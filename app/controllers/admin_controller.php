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
        parent::checkBanned();
        parent::checkLogged();
        if ($_SESSION['user']) {
            $_SESSION['user'] = null;
            Redirect::to('/');
        } else {
            View::make('failed.html');
        }
    }

    public static function login() {
        parent::checkBanned();
        $name = filter_input(INPUT_POST, 'name');
        $password = filter_input(INPUT_POST, 'password');
        $admin = Admin::findByName($name);
        if ($admin && $admin->checkPassword($password)) {
            $_SESSION['user'] = $admin->id;
            Redirect::to('/');
        } else {
            Redirect::back(array('errors' => array('Login failed')));
        }
    }

    public static function ban() {
        parent::checkBanned();
        parent::checkLogged();
        $id = filter_input(INPUT_POST, 'id');
        $user = User::findById($id);
        self::banUser($user);
        Redirect::back();
    }

    public static function banUser($user) {
        if ($user) {
            $user->delete();
            Thread::deleteEmpty();
            $ban = new Banned(array('ip' => $user->ip));
            $ban->save();
        }
    }

    public static function manage() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin) {
            $boards = Board::all();
            $admins = Admin::all();
            View::make('manage.html', array('boards' => $boards, 'admin' => $admin, 'admins' => $admins));
        } else {
            View::make('failed.html');
        }
    }

    public static function change($id) {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin && ($admin->super || $admin->id === (int) $id)) {
            $boards = Board::all();
            View::make('change.html', array('boards' => $boards, 'admin' => $admin, 'id' => $id));
        } else {
            Redirect::back(array('errors' => array('No permission to access page')));
        }
    }

    public static function changePassword() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        $id = filter_input(INPUT_POST, 'id');
        if ($admin && ($admin->super || $admin->id === (int) $id)) {
            $target = Admin::find($id);
            $old = filter_input(INPUT_POST, 'old');
            if (!$admin->checkPassword($old)) {
                Redirect::back(array('errors' => array('Wrong password')));
            }
            $new = filter_input(INPUT_POST, 'new');
            if (strlen($new) < 8) {
                Redirect::back(array('errors' => array('Password cannot smaller than 8 characters')));
            }
            $confirm = filter_input(INPUT_POST, 'confirm');
            if ($new === $confirm) {
                $target->setPassword($new);
            } else {
                Redirect::back(array('errors' => array('New passwords don\'t match')));
            }
            Redirect::to('/manage');
        } else {
            Redirect::back(array('errors' => array('No permission to change password')));
        }
    }

    public static function delete() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        $id = filter_input(INPUT_POST, 'id');
        if ($admin && ($admin->super || $admin->id === (int) $id)) {
            $target = Admin::find($id);
            $target->delete();
            if ($admin->id === (int) $id) {
                AdminController::logout();
            }
            Redirect::to('/manage');
        } else {
            Redirect::back(array('errors' => array('No permission to delete admin')));
        }
    }

    public static function create() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin && $admin->super) {
            $boards = Board::all();
            View::make('createAdmin.html', array('boards' => $boards, 'admin' => $admin));
        } else {
            Redirect::back(array('errors' => array('No permission to create admin')));
        }
    }

    public static function createAdmin() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin && $admin->super) {
            $password = filter_input(INPUT_POST, 'password');
            if (strlen($password) < 8) {
                Redirect::back(array('errors' => array('Password cannot smaller than 8 characters')));
            }
            $confirm = filter_input(INPUT_POST, 'confirm');
            if ($password === $confirm) {
                $name = filter_input(INPUT_POST, 'name');
                $email = filter_input(INPUT_POST, 'email');
                $a = new Admin(array('email' => $email, 'name' => $name, 'super' => false));
                $a->setPassword($password);
                $errors = $a->errors();
                if (count($errors) === 0) {
                    $a->save();
                    Redirect::to('/manage');
                } else {
                    Redirect::back(array('errors' => $errors));
                }
            } else {
                Redirect::back(array('errors' => array('Passwords don\'t match')));
            }
        } else {
            Redirect::back(array('errors' => array('No permission to create admin')));
        }
    }

}
