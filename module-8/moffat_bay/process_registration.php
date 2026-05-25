<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = trim($_POST['first_name'] ?? '');
    $lname = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['telephone'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $conf  = $_POST['password_confirm'] ?? '';

    if ($fname === '' || $lname === '' || $email === '' || $pass === '' || $conf === '') {
        header("Location: registration.php?error=All required fields must be completed.");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: registration.php?error=Please enter a valid email address.");
        exit();
    }

    if ($pass !== $conf) {
        header("Location: registration.php?error=Passwords do not match.");
        exit();
    }

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO customers (email, first_name, last_name, telephone, password_hash) 
                VALUES (:email, :fname, :lname, :phone, :pass_hash)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'email'     => $email,
            'fname'     => $fname,
            'lname'     => $lname,
            'phone'     => $phone,
            'pass_hash' => $hashed_password
        ]);

        header("Location: login.php?success=Account created successfully. Please log in to continue your booking.");
        exit();

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { 
            header("Location: registration.php?error=This email is already registered.");
        } else {
            header("Location: registration.php?error=Registration failed. Please try again.");
        }
        exit();
    }
}

header("Location: registration.php");
exit();