<?php

function debug($variable): string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);  
}

function randomId($length): string {
    return bin2hex(random_bytes($length));
}

function sanitize($html): string {
    return htmlspecialchars($html);
}

function isAuth(): void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

function validate($files) {
    for ($i = 0; $i < count($files['type']); $i++) {
        if ($files['type'][$i] !== 'application/pdf') {
            return false;
        }
    }
    return true;
}

function renameFile($path) {
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $name = randomId(64) . '.' . $ext;
    return $name;
}

function uploadFile($file, $name) {
    $destination = '../uploads/files/';
    return move_uploaded_file($file, $destination . $name);
}

function fileToBase64($path) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}