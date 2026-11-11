<?php 
require_once__DIR__. '/../src/validation.php';
require_once__DIR__. '/../src/storage.php';


$errors = []; 
$input = [
    'title' => '',
    'description' => '',
    'priority' => 'Medium',
    'due' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // original code was not working so ai prompt "PHP code not working help me fix it"
    $input = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'priority' => $_POST['priority'] ?? 'Medium',
        'due' => $_POST['due'] ?? ''
    ];

    $result = validateTask($input);
    $errors = $result['errors'];

    if (empty($errors)) {
        $tasks = getTasks();

        $tasks[] = [
            'id' => bin2hex(random_bytes(16)),
            'title' => $result['data']['title'],
            'description' => $result['data']['description'],
            'priority' => $result['data']['priority'],
            'due' => $result['data']['due'],
            'completed' => false
        ];

        saveTasks($tasks);

        session_start();
        $_SESSION['flash'] = 'Task created successfully!';

        header('Location: index.php');
        exit;
    }
}

function escape($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
} ?>

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
        <?php if (isset($errors['title'])): ?>
            <p class="error"><?= escape($errors['title']) ?></p>
        <?php endif; ?>

        <label for="description"> Task Description:</label>
        <textarea id="description" name="description"></textarea>
        <?php if (isset($errors['description'])): ?>
            <p class="error"><?= escape($errors['description']) ?></p>
        <?php endif; ?>

        <label for="priority">Priority</label>
        <select id="priority" name="priority">
            <?php foreach (['Low', 'Medium', 'High'] as $priority): ?>
                <option value="<?= $priority ?>"
                    <?= $input['priority'] === $priority ? 'selected' : '' ?>>
                    <?= $priority ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['priority'])): ?>
            <p class="error"><?= escape($errors['priority']) ?></p>
        <?php endif; ?>

        <label for="due_date"> Due Date:</label>
        <input type="date" id="due_date" name="due_date" value="<?= escape($input['due']) ?>">
        <?php if (isset($errors['due'])): ?>
            <p class="error"><?= escape($errors['due']) ?></p>
        <?php endif; ?>
        <button type="submit">Create Task</button>
        <a href="index.php">Cancel</a>
        </form>
    </main>
</body>
</html>