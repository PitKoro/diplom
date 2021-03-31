<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">

    <!-- Bootstrap CSS -->
    <link type="text/css" rel="stylesheet" href="../../public/vendor/bootstrap/css/bootstrap.min.css">

    <title>Профиль</title>

</head>
<body>
    <? require "blocks/navbar.php"; ?>
    
    <div class="container mt-5">
        <!-- Профиль -->
        <form>
            <img src="<?= '../../'.$_SESSION['user']['avatar'] ?>" width="200" alt="">
            <h2 style="margin: 10px 0;"><?= $_SESSION['user']['full_name'] ?></h2>
            <h2>Статус: <?= $_SESSION['user']['status'] ?></h2></br>
            <h6>user: <? print_r( $_SESSION['user'])?></h6></br>
            <a href="#"><?= $_SESSION['user']['email'] ?></a>
            <a href="../php/logout.php" class="logout">Выход</a>
        </form>
    </div>
    

    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>