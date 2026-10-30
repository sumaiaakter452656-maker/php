<?php
// =============================================
// সব পেজে লাগবে এমন helper function
// =============================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

// XSS থেকে বাঁচতে আউটপুট এর আগে সবসময় e() ব্যবহার করবে
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . BASE_URL . ltrim($path, '/'));
    exit;
}

// ---------- CSRF ----------
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        die('Invalid request (CSRF token mismatch).');
    }
}

// ---------- Flash message (একবার দেখিয়ে মুছে যায়) ----------
function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function show_flash(): string
{
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return '<div class="alert alert-' . e($f['type']) . '">' . e($f['message']) . '</div>';
}

// ---------- Login অবস্থা ----------
function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function current_role(): ?string
{
    return $_SESSION['role'] ?? null;
}

// role অনুযায়ী dashboard এর path
function dashboard_path(string $role): string
{
    return match ($role) {
        'admin'    => 'admin/dashboard.php',
        'employer' => 'employer/dashboard.php',
        default    => 'seeker/dashboard.php',
    };
}
