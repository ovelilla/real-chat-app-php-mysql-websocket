<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chat">

    <title>Chat | <?php echo $title ?? '' ?></title>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="preload" href="/build/css/app.css" as="style">
    <link rel="stylesheet" href="/build/css/app.css">
</head>

<body>
    <?php
    echo $content;

    include __DIR__  . '/layout/scripts.php';
    ?>
</body>

</html>