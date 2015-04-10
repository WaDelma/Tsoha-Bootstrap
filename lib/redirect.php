<?php

class Redirect {

    public static function to($path, $message = null) {
        // Katsotaan onko $message parametri asetettu
        if (!is_null($message)) {
            // Jos on, lisätään se sessioksi JSON-muodossa
            $_SESSION['flash_message'] = json_encode($message);
        }

        // Ohjataan käyttäjä annettuun polkuun
        header('Location: ' . BASE_PATH . $path);

        exit();
    }

    public static function back($message = null) {
        if (!is_null($message)) {
            $_SESSION['flash_message'] = json_encode($message);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

}
