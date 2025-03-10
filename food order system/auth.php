<?php
session_start();

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserRole() {
    return $_SESSION['role'] ?? null;
}

function getUserEmail() {
    return $_SESSION['email'] ?? null;
}

function setUserSession($userId, $email, $role) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;
    $_SESSION['last_activity'] = time();
}

function clearUserSession() {
    session_unset();
    session_destroy();
    
    // Clear session cookie
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], 
             $params['secure'], $params['httponly']);
}

function checkSessionTimeout($timeout = 3600) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        clearUserSession();
        return true;
    }
    $_SESSION['last_activity'] = time();
    return false;
}

function requireRole($requiredRole) {
    if (!isLoggedIn() || getUserRole() !== $requiredRole) {
        header("Location: login.php");
        exit();
    }
}
?>