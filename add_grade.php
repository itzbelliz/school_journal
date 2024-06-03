<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = $_POST['student_name'];
    $subject_name = $_POST['subject_name'];
    $grade = $_POST['grade'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $teacher_id = $_SESSION['user_id'];

    // Find student ID
    $studentQuery = $conn->prepare("SELECT id FROM users WHERE fullname=? AND role='student'");
    $studentQuery->bind_param('s', $student_name);
    $studentQuery->execute();
    $studentResult = $studentQuery->get_result();
    $student = $studentResult->fetch_assoc();
    if (!$student) {
        // echo "Student not found.";
        $headline = "Nie znaleziono takiego ucznia";
        $backUrl = "dashboard.php"; 
        include 'template.php';
        exit();
    }
    $student_id = $student['id'];

    // Find subject ID
    $subjectQuery = $conn->prepare("SELECT id FROM subjects WHERE name=?");
    $subjectQuery->bind_param('s', $subject_name);
    $subjectQuery->execute();
    $subjectResult = $subjectQuery->get_result();
    $subject = $subjectResult->fetch_assoc();
    if (!$subject) {
        // echo "Subject not found.";
        $headline = "Nie znaleziono takiego przedmiotu";
        $backUrl = "dashboard.php"; 
        include 'template.php';
        exit();
    }
    $subject_id = $subject['id'];

    // Insert grade
    $insertQuery = $conn->prepare("INSERT INTO grades (user_id, subject_id, grade, date, description, teacher_id) VALUES (?, ?, ?, ?, ?, ?)");
    $insertQuery->bind_param('iiissi', $student_id, $subject_id, $grade, $date, $description, $teacher_id);

    if ($insertQuery->execute()) {
        // echo "Grade added successfully.";
        $title = "Dashboard Teacher";
        $headline = "Dodano ocenę prawidłowo";
        $backUrl = "dashboard.php"; 
        include 'template.php';
        exit;
    } else {
        // echo "Failed to add grade.";
        $title = "Dashboard Teacher";
        $headline = "Nie dodano oceny";
        $backUrl = "dashboard.php"; 
        include 'template.php';
        exit();
    }
}
?>