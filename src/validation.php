<?php

function validateTask(array $input): array {
    $title = trim($input['title'] ?? '');
    $description = trim($input['description'] ?? '');
    $priority = $input['priority'] ?? 'Medium';
    $due = trim($input['due'] ?? '');

    $errors = [];

    if ($title === '') {
        $errors['title'] = 'Please enter a task title.';
    } elseif (strlen($title) > 100) {
        $errors['title'] = 'Title cannot exceed 100 characters.';
    }

    if (strlen($description) > 1000) {
        $errors['description'] = 'Description cannot exceed 1000 characters.';
    }

    if (!in_array($priority, ['Low', 'Medium', 'High'], true)) {
        $errors['priority'] = 'Please select a valid priority.';
    }

    if ($due !== '') {
        $validFormat = preg_match('/^\d{4}-\d{2}-\d{2}$/', $due);

        if (!$validFormat) {
            $errors['due'] = 'Please enter a valid date.';
        } else {
            [$year, $month, $day] = explode('-', $due);

            if (!checkdate((int)$month, (int)$day, (int)$year)) {
                $errors['due'] = 'Please enter a valid date.';
            }
        }
    }

    return [
        'errors' => $errors,
        'data' => [
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
            'due' => $due !== '' ? $due : null
        ]
    ];
}