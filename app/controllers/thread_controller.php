<?php

/**
 * Description of thread_controller
 *
 * @author delma
 */
class ThreadController extends BaseController {

    public static function thread($board, $thread) {
        echo $board + $thread;
    }

}
