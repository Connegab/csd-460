<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function authenticateUser(string $email, string $password): ?array
{
    global $pdo;

    $statement = $pdo->prepare(
        'SELECT customer_id, first_name, last_name, email, password_hash
         FROM customers
         WHERE email = :email
         LIMIT 1'
    );
    $statement->execute(['email' => $email]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return null;
    }

    if (!password_verify($password, $user['password_hash'])) {
        return null;
    }

    unset($user['password_hash']);

    return $user;
}

function logUserIn(array $user): void
{
    session_regenerate_id(true);

    $_SESSION['user'] = [
        'customer_id' => $user['customer_id'],
        'first_name'  => $user['first_name'],
        'last_name'   => $user['last_name'],
        'email'       => $user['email'],
    ];
}

function getLoggedInUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function requireLogin(): void
{
    if (getLoggedInUser() !== null) {
        return;
    }

    header('Location: login.php');
    exit;
}

function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
