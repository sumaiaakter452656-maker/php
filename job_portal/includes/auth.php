<?php
// =============================================
// Protected পেজের একদম উপরে include করবে:
//   require_once '../includes/auth.php';
//   require_role('employer');
// =============================================

require_once __DIR__ . '/functions.php';

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('warning', 'আগে লগইন করুন।');
        redirect('login.php');
    }
}

function require_role(string ...$roles): void
{
    require_login();
    if (!in_array(current_role(), $roles, true)) {
        http_response_code(403);
        die('এই পেজ দেখার অনুমতি আপনার নেই।');
    }
}
