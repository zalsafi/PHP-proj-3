<?php
session_start();

require_once __DIR__ . '/../src/storage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';

if (!is_string($id) || $id === '') {
    $_SESSION['flash'] = 'Invalid task.';
    header('Location: index.php');
    exit;
}

$tasks = getTasks();
$found = false;

if ($action === 'complete') {
    foreach ($tasks as &$task) {
        if (($task['id'] ?? '') === $id) {
            $task['completed'] = true;
            $found = true;
            break;
        }
    }
    unset($task);

} elseif ($action === 'delete') {
    $updatedTasks = [];

    foreach ($tasks as $task) {
        if (($task['id'] ?? '') === $id) {
            $found = true;
        } else {
            $updatedTasks[] = $task;
        }
    }

    $tasks = $updatedTasks;

} else {
    $_SESSION['flash'] = 'Invalid action.';
    header('Location: index.php');
    exit;
}

if ($found) {
    saveTasks($tasks);
    $_SESSION['flash'] = $action === 'complete'
        ? 'Task marked as complete!'
        : 'Task deleted successfully!';
} else {
    $_SESSION['flash'] = 'Task not found.';
}

header('Location: index.php');
exit;