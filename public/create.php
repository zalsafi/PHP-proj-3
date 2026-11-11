<?php 
require_once__DIR__. '/../src/validation.php';
require_once__DIR__. '/../src/storage.php';

?>
$errors = [];
$input = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    'title' => $_POST['title'] ?? '',
    'description' => $_POST['description'] ?? '',
   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Create Task</title>
    <link rel='stylesheet' href='assets/style.css'>
</head>
<body>
    <header>
        <h1>TaskPad</h1>
        <p>Create a new task below.</p>
    </header>

    <main> 
        <h2>Add a Task</h2>
        <form action="create.php" method="POST">
        <label for="title"> Task Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description"> Task Description:</label>
        <textarea id="description" name="description"></textarea>

        <label for="priority"> Priority:</label>
        <select id="priority" name="priority">

        <label for="due_date"> Due Date:</label>
        <input type="date" id="due_date" name="due_date">
        
        <button type="submit">Create Task</button>
        <a href="index.php">Cancel</a>
        </form>
    </main>
</body>
</html>