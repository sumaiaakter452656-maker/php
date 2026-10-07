<?php
if (session_status() === PHP_SESSION_NONE) {
    $sessionPath = session_save_path();

    if ($sessionPath !== '' && (!is_dir($sessionPath) || !is_writable($sessionPath))) {
        $fallbackPath = sys_get_temp_dir();

        if (!is_dir($fallbackPath) || !is_writable($fallbackPath)) {
            die('PHP session storage is unavailable.');
        }

        session_save_path($fallbackPath);
    }

    session_start();
}
