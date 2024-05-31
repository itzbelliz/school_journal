<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style/dashboard.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="img/HYS_1.png" alt="HYS Logo">
        </div>
        <nav class="nav">
            <ul>
                <li><a href="#">Plan lekcji</a></li>
                <li><a href="#">Oceny</a></li>
                <li><a href="#">Nieobecności</a></li>
                <li><a href="#">Info/Uwagi/Wiadomości</a></li>
                <li>
                <form action="logout.php" method="post">
                    <button type="submit" name="logout">Wyloguj</button>
                </form>
                </li>
            </ul>
        </nav>
        <div class="user-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
    </header>
    <main class="main">
        <section class="welcome">
            <i class="fas fa-user-graduate"></i>
            <div>Powitanie</div>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius quia rem adipisci nostrum ipsam, officia distinctio laudantium molestias sequi aliquam quos consectetur officiis impedit, nemo nesciunt quas illum eveniet veritatis.</p>
        </section>
        <section class="average">
            <i class="fas fa-gauge-high"></i>
            <div>Twoja średnia</div>
            <p class="avgrade">4.5</p>
        </section>
        <section class="schedule">
            <i class="fas fa-clipboard"></i>
            <div>Plan zajęć na dziś</div>
            <ol>
                <li>Matematyka</li>
                <li>Język Polski</li>
                <li>Język Angielski</li>
            </ol>
        </section>
        <section class="recent-grades">
            <div>Ostatnie oceny</div>
        </section>
        <section class="messages">
            <i class="fas fa-envelope"></i>
            <div>Wiadomości</div>
        </section>
        <section class="calendar">
            <i class="fas fa-calendar-alt"></i>
            <div>Kalendarz</div>
        </section>
    </main>
</body>
</html>