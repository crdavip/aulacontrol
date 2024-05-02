<?php

// Si retorna true, significa que hay campos vacios.
function checkNotEmpty(array $inputs)
{
    foreach ($inputs as $input) {
        if (strlen(trim($input)) < 1) {
            return true;
        }
    }
    return false;
}

// Si retorna true, significa que el email es correcto.
function checkEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

// Si retorna true, significa que las contraseñas coinciden.
function checkPassword($pass, $repass)
{
    if (strcmp($pass, $repass) === 0) {
        return true;
    } else {
        return false;
    }
}

// Si retorna true, significa que el campo ya existe.
function checkExistence($pdo, $table, array $columns, array $values)
{
    $sql = "SELECT * FROM $table WHERE " . implode(' = ? AND ', $columns) . " = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function getColumns($pdo, $table, array $columns)
{
    $sql = "SELECT DISTINCT " . implode(',', $columns) . " FROM $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function getValue($pdo, $table, $column, $condition, $value)
{
    $sql = "SELECT $column FROM $table WHERE $condition = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$value]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["$column"];
}

function getIcon($type) {
    if ($type === 'OK') {
        return '<i class="fa-solid fa-circle-check"></i>&nbsp';
    } elseif ($type === 'Err') {
        return '<i class="fa-solid fa-triangle-exclamation"></i>&nbsp';
    }
}