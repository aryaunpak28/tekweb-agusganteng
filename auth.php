<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();

    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        header('Location: index_pelanggan.php');
        exit;
    }
}

function require_pelanggan(): void
{
    require_login();

    if (($_SESSION['user']['role'] ?? '') !== 'pelanggan') {
        header('Location: index.php');
        exit;
    }
}
