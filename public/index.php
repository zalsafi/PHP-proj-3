<?php
session_start();

require_once __DIR__ . '/../src/storage.php';

$tasks = getTasks();

$search = trim($_GET['q'] ?? '');
$priorityFilter = $_GET['priority'] ?? '';

$filteredTasks = array_filter($tasks, function ($task) use ($search, $priorityFilter) {
    $matchesSearch = $search === ''
        || stripos($task['title'], $search) !== false
        || stripos($task['description'] ?? '', $search) !== false;

    $matchesPriority = $priorityFilter === ''
        || ($task['priority'] ?? '') === $priorityFilter;

    return $matchesSearch && $matchesPriority;
});

$totalTasks = count($tasks);
$completedTasks = count(array_filter($tasks, fn($task) => !empty($task['completed'])));

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

function escape($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskPad | My Tasks</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <h1>TaskPad</h1>
    <p>Your simple task tracker</p>
</header>

<main>
    <h2>My Tasks</h2>

    <?php if ($flash !== ''): ?>
        <p class="success"><?= escape($flash) ?></p>
    <?php endif; ?>

    <p>Total tasks: <?= $totalTasks ?></p>
    <p>Completed tasks: <?= $completedTasks ?></p>

    <a href="create.php">+ Add a Task</a>

    <form method="GET" action="index.php">
        <label for="q">Search tasks</label>
        <input
            type="text"
            id="q"
            name="q"
            placeholder="Search by title or description"
            value="<?= escape($search) ?>"
        >

        <label for="priority">Filter by priority</label>
        <select id="priority" name="priority">
            <option value="">All priorities</option>
            <?php foreach (['Low', 'Medium', 'High'] as $priority): ?>
                <option value="<?= $priority ?>"
                    <?= $priorityFilter === $priority ? 'selected' : '' ?>>
                    <?= $priority ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Apply Filters</button>
        <a href="index.php">Clear</a>
    </form>

    <section>
        <?php if (empty($filteredTasks)): ?>
            <p>No tasks found.</p>
        <?php else: ?>
            <?php foreach ($filteredTasks as $task): ?>
                <article class="task">
                    <h3><?= escape($task['title']) ?></h3>

                    <p><?= escape($task['description'] ?? '') ?></p>
                    <p>Priority: <?= escape($task['priority']) ?></p>

                    <?php if (!empty($task['due'])): ?>
                        <p>Due: <?= escape($task['due']) ?></p>
                    <?php endif; ?>

                    <p>
                        Status:
                        <?= !empty($task['completed']) ? 'Completed' : 'Pending' ?>
                    </p>

                    <?php if (empty($task['completed'])): ?>
                        <form method="POST" action="actions.php">
                            <input type="hidden" name="id" value="<?= escape($task['id']) ?>">
                            <input type="hidden" name="action" value="complete">
                            <button type="submit">Complete</button>
                        </form>
                    <?php endif; ?>

                    <form method="POST" action="actions.php">
                        <input type="hidden" name="id" value="<?= escape($task['id']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit">Delete</button>
                    </form>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

</body>
</html>