<?php

/**
 * Description of Pager
 *
 * @author delma
 */
class Pager {

    public static function page(&$data, $count, $page_size) {
        $data['pages'] = floor($count / $page_size) + 1;
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        if (!isset($page)) {
            $page = 1;
        }
        if ($page < 1 || $page > $data['pages']) {
            Redirect::to('/', array('errors' => array('Page number is invalid')));
        }
        $data['cur_page'] = $page;
        if ($page != 1) {
            $data['prev_page'] = $page - 1;
        }
        if ($page != $data['pages']) {
            $data['next_page'] = $page + 1;
        }
    }

}
