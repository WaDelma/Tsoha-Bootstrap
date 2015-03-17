<?php

/**
 * Description of front_page_controller
 *
 * @author delma
 */
class FrontPageController extends BaseController {

    public static function frontpage() {
        View::make('home.html', array('boards' => array('v', 'pol')));
    }

}
