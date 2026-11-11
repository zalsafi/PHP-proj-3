<?php

function getTasks(): array {
    $file = __DIR__ . '/../data/tasks.json';

    if (!file_exists($file)) {
        return [];
    }

    $data = file_get_contents($file);
    $tasks = json_decode($data, true);

    return is_array($tasks) ? $tasks : [];
}

function saveTasks(array $tasks): void {
    $file = __DIR__ . '/../data/tasks.json';

    file_put_contents(
        $file,
        json_encode($tasks, JSON_PRETTY_PRINT),
        LOCK_EX
    );
}