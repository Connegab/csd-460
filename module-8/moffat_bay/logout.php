<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

logoutUser();

header('Location: login.php');
exit;

