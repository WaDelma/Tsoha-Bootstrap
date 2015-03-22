<?php

/**
 * Description of board_controller
 *
 * @author delma
 */
class BoardController extends BaseController {

    public static function board($board) {
        View::make('board.html', array('boards' => array('v', 'pol'), 'board' => $board, 'threads' => array(1 => array("asd", "wasd", "kasd"), 102 => array(), 4035 => array("hasd"))));
    }

}
