<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to = 'mantik4879@gmail.com';
    $subject = 'New Contact Form Submission';
    $headers = "From: $email\r\n";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    if (mail($to, $subject, $body, $headers)) {
        echo "Отправлено!";
    } else {
        echo "Произошла ошибка при отправлении";
    }
} else {
    echo "Неверный запрос.";
}
session_start();

function connectToDatabase() {
    $dsn = 'mysql:host=localhost;dbname=testdb';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

function handleFormSubmission() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        if (validateFormData($name, $email, $message)) {
            saveToDatabase($name, $email, $message);
            $_SESSION['success'] = 'Form submitted successfully!';
        } else {
            $_SESSION['error'] = 'Invalid form data.';
        }

        header('Location: form.php');
        exit;
    }
}

function validateFormData($name, $email, $message) {
    return !empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message);
}

function saveToDatabase($name, $email, $message) {
    $pdo = connectToDatabase();
    $sql = 'INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':name' => $name, ':email' => $email, ':message' => $message]);
}

function fetchData() {
    $pdo = connectToDatabase();
    $sql = 'SELECT * FROM contacts';
    $stmt = $pdo->query($sql);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    return json_encode($data);
}

if (isset($_POST['request']) && $_POST['request'] == 'getData') {
    echo fetchData();
    exit;
}

if (isset($_POST['request']) && $_POST['request'] == 'sendData') {
    echo json_encode(['success' => true]);
    exit;
}

handleFormSubmission();

