<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_POST['user_id'];
  $subject_id = $_POST['subject_id'];
  $teacher_id = $_POST['teacher_id'];
  $day_of_week = $_POST['day_of_week'];
  $start_time = $_POST['start_time'];
  $end_time = $_POST['end_time'];

  $query = $conn->prepare("INSERT INTO schedule (user_id, subject_id, teacher_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
  $query->bind_param('iiisss', $user_id, $subject_id, $teacher_id, $day_of_week, $start_time, $end_time);

  if ($query->execute()) {
    $title = "Dashboard Admin";
    $headline = "Plan zajęć dodany pomyślnie";
    $backUrl = "dashboard.php";
    include 'template.php';
    exit;
  } else {
    $title = "Dashboard Admin";
    $headline = "Błąd: " . $query->error;
    $backUrl = "dashboard.php";
    include 'template.php';
    exit;
  }
}

// Pobieranie listy użytkowników (tylko studentów)
$usersQuery = $conn->prepare("SELECT id, fullname FROM users WHERE role = 'student'");
$usersQuery->execute();
$usersResult = $usersQuery->get_result();
$users = $usersResult->fetch_all(MYSQLI_ASSOC);

// Pobieranie listy przedmiotów
$subjectsQuery = $conn->prepare("SELECT id, name FROM subjects");
$subjectsQuery->execute();
$subjectsResult = $subjectsQuery->get_result();
$subjects = $subjectsResult->fetch_all(MYSQLI_ASSOC);

// Pobieranie listy nauczycieli
$teachersQuery = $conn->prepare("SELECT id, fullname FROM users WHERE role = 'teacher'");
$teachersQuery->execute();
$teachersResult = $teachersQuery->get_result();
$teachers = $teachersResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dodaj Plan Zajęć</title>
  <link rel="stylesheet" href="style/register.css">
</head>

<body>
  <div class="background">
    <div class="big-text">HYS</div>
    <div class="container">
      <div class="logo">
        <img src="img/HYS_1.png" alt="Logo" id="logo">
      </div>

      <main class="main">
        <section class="add-schedule add-grades">
          <h3>Dodaj Plan Zajęć</h3>
          <form action="add_schedule.php" method="post">
            <div class="input-group">
              <label>Użytkownik:
                <select name="user_id" required>
                  <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user['id']) ?>"><?= htmlspecialchars($user['fullname']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </label>
            </div>
            <div class="input-group">
              <label>Przedmiot:
                <select name="subject_id" required>
                  <?php foreach ($subjects as $subject): ?>
                    <option value="<?= htmlspecialchars($subject['id']) ?>"><?= htmlspecialchars($subject['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </label>
            </div>

            <div class="input-group">
              <label>Nauczyciel:
                <select name="teacher_id" required>
                  <?php foreach ($teachers as $teacher): ?>
                    <option value="<?= htmlspecialchars($teacher['id']) ?>"><?= htmlspecialchars($teacher['fullname']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </label>
            </div>

            <div class="input-group">
              <label>Dzień tygodnia:
                <select name="day_of_week" required>
                  <option value="Monday">Poniedziałek</option>
                  <option value="Tuesday">Wtorek</option>
                  <option value="Wednesday">Środa</option>
                  <option value="Thursday">Czwartek</option>
                  <option value="Friday">Piątek</option>
                  <option value="Saturday">Sobota</option>
                  <option value="Sunday">Niedziela</option>
                </select>
              </label>
            </div>

            <div class="input-group">
              <label>Godzina rozpoczęcia:
                <input type="time" name="start_time" required>
              </label>
            </div>

            <div class="input-group">
              <label>Godzina zakończenia:
                <input type="time" name="end_time" required>
              </label>
            </div>

            <button type="submit">Dodaj</button>
          </form>
        </section>
      </main>
      <section id="go-back">
        <a href="dashboard.php">Wróć na stronę główną</a>
      </section>

      
    </div>
    <footer>
        <p>&copy; Dziennik Lekcyjny HYS</p>
      </footer>
  </div>
</body>

</html>