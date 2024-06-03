<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="background">
        <div class="big-text">HYS</div>
        <div class="container">
            <div class="logo">
                <img src="img/HYS_1.png" alt="HYS Logo">
            </div>
            <h1><?php echo $headline; ?></h1>
            <div class="go-back">
            <a href="<?php echo $backUrl; ?>">Powrót</a>
            </div>
        </div>
        
        <footer>
            <p>&copy; Dziennik Lekcyjny HYS</p>
        </footer>
    </div>

</body>

</html>
