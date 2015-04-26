<?php

/**
 * Description of manage_boards_controller
 *
 * @author delma
 */
class ManageBoardsController extends BaseController {

    public static function manageBoards() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin && $admin->super) {
            $boards = Board::all();
            $admins = Admin::all();
            $admin = parent::get_user_logged_in();
            $adminBoard = AdminBoard::all();
            View::make('manageBoards.html', array('boards' => $boards, 'admins' => $admins, 'admin' => $admin, 'adminBoard' => $adminBoard));
        } else {
            Redirect::back(array('errors' => array('No permission to manage boards')));
        }
    }

    public static function saveManageBoards() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin && $admin->super) {
            $ab = array();
            foreach ($_POST['adminboard'] as $a => $b) {
                foreach ($b as $c => $d) {
                    $ab[$a][$c] = true;
                }
            }
            $adminboard = new AdminBoard(array('adminBoard' => $ab));
            $adminboard->save();
            Redirect::back();
        } else {
            Redirect::back(array('errors' => array('No permission to manage boards')));
        }
    }

}
