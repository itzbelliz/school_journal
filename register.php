<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $fullname = $_POST['fullname'];

    $query = $conn->prepare("INSERT INTO users (username, password, role, fullname) VALUES (?, ?, ?, ?)");
    $query->bind_param('ssss', $username, $password, $role, $fullname);
    $query->execute();

    echo "Rejestracja zakończona pomyślnie!";
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="./style/register.css">
</head>

<body>
    <div class="background">
        <div class="big-text">HYS</div>
        <div class="container">
        <div class="logo">
            <img src="img/HYS_1.png" alt="Logo" id="logo">
        </div>

        <main>


            <h2>Rejestracja</h2>

            <form action="register.php" method="post">
                <div class="input-group">
                    <label for="fullname">Pełne imię:</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                <div class="input-group">
                    <label for="username">Nazwa użytkownika:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Hasło:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group input-group-select">
                    <label for="role" class="rola">Rola:</label>
                    <select id="role" name="role" required>
                        <option value="student">Uczeń</option>
                        <option value="teacher">Nauczyciel</option>
                    </select>
                </div>
                <button type="submit">Zarejestruj się</button>
            </form>
            <section id="go-back">
                <a href="index.html">Wróć na stronę główną</a>
            </section>
        </main>
        </div>
        <footer>
            <p>&copy; 2024 Dziennik Lekcyjny HYS</p>
        </footer>
    </div>
</body>

</html>