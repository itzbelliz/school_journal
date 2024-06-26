<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Pobieranie danych użytkownika
$query = $conn->prepare("SELECT fullname FROM users WHERE id=?");
$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$fullname = $user['fullname'];

// Pobieranie wiadomości
$messagesQuery = $conn->prepare("SELECT message, date_sent FROM messages WHERE user_id = ?");
$messagesQuery->bind_param('i', $user_id);
$messagesQuery->execute();
$messagesResult = $messagesQuery->get_result();
$messages = $messagesResult->fetch_all(MYSQLI_ASSOC);

// Pobieranie planu zajęć na tydzień dla zalogowanego użytkownika z dodatkowymi danymi nauczyciela i przedmiotu
$scheduleQuery = $conn->prepare("
    SELECT sch.subject_id, subjects.name AS subject, sch.day_of_week, sch.start_time, sch.end_time, teachers.fullname AS teacher 
    FROM schedule sch
    JOIN subjects ON sch.subject_id = subjects.id
    JOIN users teachers ON sch.teacher_id = teachers.id
    WHERE sch.user_id = ?
    ORDER BY sch.day_of_week, sch.start_time
");
$scheduleQuery->bind_param('i', $user_id);

// Pobieranie ocen
if ($role === 'teacher') {
    $gradesQuery = $conn->prepare("SELECT g.id, u.fullname AS student_name, s.name AS subject, g.grade, g.date, g.description 
                                   FROM grades g 
                                   JOIN users u ON g.user_id = u.id 
                                   JOIN subjects s ON g.subject_id = s.id 
                                   WHERE g.teacher_id = ?");
    $gradesQuery->bind_param('i', $user_id);
} else {
    $gradesQuery = $conn->prepare("SELECT g.id, s.name AS subject, g.grade, g.date, g.description 
                                   FROM grades g 
                                   JOIN subjects s ON g.subject_id = s.id 
                                   WHERE g.user_id = ?");
    $gradesQuery->bind_param('i', $user_id);
}

if ($role === 'student') {
    $gradessQuery = $conn->prepare("SELECT g.grade FROM grades g WHERE g.user_id = ?");
    $gradessQuery->bind_param('i', $user_id);
    $gradessQuery->execute();
    $gradessResult = $gradessQuery->get_result();
    $gradess = $gradessResult->fetch_all(MYSQLI_ASSOC);

    if (!empty($gradess)) {
        $suma_ocen = array_sum(array_column($gradess, 'grade'));
        $liczba_ocen = count($gradess);
        $srednia_ocen = $suma_ocen / $liczba_ocen;
    } else {
        $srednia_ocen = 0;
    }
}

$gradesQuery->execute();
$gradesResult = $gradesQuery->get_result();
$grades = $gradesResult->fetch_all(MYSQLI_ASSOC);

$scheduleQuery->execute();
$scheduleResult = $scheduleQuery->get_result();
$schedule = $scheduleResult->fetch_all(MYSQLI_ASSOC);
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
                <?php if ($role === 'admin') : ?>
                    <li><a href="add_schedule.php">Dodaj Plan Zajęć</a></li>
                <?php endif; ?>
                <li>
                    <form action="logout.php" method="post">
                        <button type="submit" name="logout" class="wyloguj">Wyloguj</button>
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
            <p>Witaj użytkowniku: <?php echo $fullname ?></p>
        </section>
        <section class="average">
            <i class="fas fa-gauge-high"></i>
            <div>Twoja średnia</div>
            <p class="avgrade">
                <?php 
                if (isset($srednia_ocen)) {
                    echo number_format($srednia_ocen, 2);
                } else {
                    echo "Brak ocen.";
                }
                ?>
            </p>
        </section>
        <section class="schedule">
            <i class="fas fa-clipboard"></i>
            <div>Plan zajęć</div>
            <table border="1">
                <tr>
                    <th>Przedmiot</th>
                    <th>Dzień tygodnia</th>
                    <th>Godzina rozpoczęcia</th>
                    <th>Godzina zakończenia</th>
                    <th>Nauczyciel</th>
                </tr>
                <?php foreach ($schedule as $item) : ?>
                    <tr>
                        <td><?= htmlspecialchars($item['subject']) ?></td>
                        <td><?= htmlspecialchars($item['day_of_week']) ?></td>
                        <td><?= htmlspecialchars($item['start_time']) ?></td>
                        <td><?= htmlspecialchars($item['end_time']) ?></td>
                        <td><?= htmlspecialchars($item['teacher']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
        <section class="recent-grades">
            <div>Ostatnie oceny</div>
            <table border="1">
                <tr>
                    <th>Przedmiot</th>
                    <th>Ocena</th>
                    <th>Data</th>
                    <th>Opis</th>
                    <?php if ($role === 'teacher') : ?>
                        <th>Student</th>
                    <?php endif; ?>
                </tr>
                <?php foreach ($grades as $grade) : ?>
                    <tr>
                        <td><?= htmlspecialchars($grade['subject']) ?></td>
                        <td><?= htmlspecialchars($grade['grade']) ?></td>
                        <td><?= htmlspecialchars($grade['date']) ?></td>
                        <td><?= htmlspecialchars($grade['description']) ?></td>
                        <?php if ($role === 'teacher') : ?>
                            <td><?= htmlspecialchars($grade['student_name']) ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
        <section class="messages">
            <i class="fas fa-envelope"></i>
            <div>Wiadomości</div>
            <table border="1">
                <tr>
                    <th>Wiadomość</th>
                    <th>Data wysłania</th>
                </tr>
                <?php foreach ($messages as $message) : ?>
                    <tr>
                        <td><?= htmlspecialchars($message['message']) ?></td>
                        <td><?= htmlspecialchars($message['date_sent']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
        <section class="calendar">
            <i class="fas fa-calendar-alt"></i>
            <div>Kalendarz</div>
            <?php
            setlocale(LC_TIME, 'pl_PL.UTF-8');
            function draw_calendar($month, $year) {
                $headings = array('Pon', 'Wt', 'Śr', 'Czw', 'Pt', 'Sob', 'Nd');
                $calendar = '<table class="calendar">';
                $calendar .= '<tr class="calendar-row"><th class="calendar-day-head">' 
                            . implode('</th><th class="calendar-day-head">', $headings) 
                            . '</th></tr>';
                $running_day = date('N', mktime(0,0,0,$month,1,$year));
                $days_in_month = date('t', mktime(0,0,0,$month,1,$year));
                $day_counter = 0;
                $days_in_this_week = 1;
                $calendar .= '<tr class="calendar-row">';
                for ($x = 1; $x < $running_day; $x++) {
                    $calendar .= '<td class="calendar-day-np"></td>';
                    $days_in_this_week++;
                }
                for ($list_day = 1; $list_day <= $days_in_month; $list_day++) {
                    $calendar .= '<td class="calendar-day">' . $list_day . '</td>';
                    
                    if ($running_day == 7) {
                        $calendar .= '</tr>';
                        if (($day_counter + 1) <= $days_in_month) {
                            $calendar .= '<tr class="calendar-row">';
                        }
                        $running_day = 0;
                        $days_in_this_week = 0;
                    }
                    $days_in_this_week++;
                    $running_day++;
                    $day_counter++;
                }
                if ($days_in_this_week < 8) {
                    for ($x = 1; $x <= (8 - $days_in_this_week); $x++) {
                        $calendar .= '<td class="calendar-day-np"></td>';
                    }
                }
                $calendar .= '</tr>';
                $calendar .= '</table>';
                return $calendar;
            }

            $month = date('m');
            $year = date('Y');
            $monthName = strftime('%B', mktime(0, 0, 0, $month, 10));
            echo '<h2>' . ucfirst($monthName) . ' ' . $year . '</h2>';
            echo draw_calendar($month, $year);
            ?>
        </section>
    </main>

    <?php if ($role === 'teacher'): ?>
        <main class="main">
        <section class="add-grades">
        <h3>Add Grade</h3>
        <form class="teacher-add-grade-form" action="add_grade.php" method="post">
            <label>Student Name:
                <input type="text" name="student_name" required>
            </label>
            <label>Subject Name:
                <input type="text" name="subject_name" required>
            </label>
            <label>Grade:
                <input type="number" name="grade" required>
            </label>
            <label>Date:
                <input type="date" name="date" required>
            </label>
            <label>Description:
                <textarea name="description"></textarea>
            </label>
            <button type="submit">Add</button>
        </form>
        </section>
        </main>
    <?php endif; ?>

    <div class="footer">
        <footer>
          <p>&copy Dziennik Lekcyjny HYS</p>
         </footer>
    </div>
</body>
</html>