<?php

$source = __DIR__ . '/database/database.sqlite';
$output = __DIR__ . '/database/quintech-mysql.sql';

$pdo = new PDO('sqlite:' . $source);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tables = $pdo->query("
    SELECT name
    FROM sqlite_master
    WHERE type = 'table'
      AND name NOT LIKE 'sqlite_%'
    ORDER BY name
")->fetchAll(PDO::FETCH_COLUMN);

$out = "-- Quin Tech SQLite -> MariaDB export\n";
$out .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

function mysqlType(string $type): string
{
    $type = strtoupper(trim($type));

    if ($type === '') {
        return 'LONGTEXT';
    }

    if (preg_match('/^VARCHAR$/', $type)) {
        return 'VARCHAR(255)';
    }

    if (preg_match('/^CHAR$/', $type)) {
        return 'CHAR(255)';
    }

    if ($type === 'INTEGER') {
        return 'BIGINT';
    }

    if ($type === 'INT') {
        return 'INT';
    }

    if ($type === 'REAL') {
        return 'DOUBLE';
    }

    if ($type === 'FLOAT') {
        return 'DOUBLE';
    }

    if ($type === 'DOUBLE') {
        return 'DOUBLE';
    }

    if ($type === 'BOOLEAN') {
        return 'TINYINT(1)';
    }

    if ($type === 'BLOB') {
        return 'LONGBLOB';
    }

    if ($type === 'TEXT') {
        return 'LONGTEXT';
    }

    if (preg_match('/^VARCHAR\(\d+\)$/', $type)) {
        return $type;
    }

    if (preg_match('/^CHAR\(\d+\)$/', $type)) {
        return $type;
    }

    return $type;
}

foreach ($tables as $table) {

    $safeTable = str_replace('`', '``', $table);

    $columns = $pdo->query(
        "PRAGMA table_info(" . $pdo->quote($table) . ")"
    )->fetchAll(PDO::FETCH_ASSOC);

    $out .= "DROP TABLE IF EXISTS `$safeTable`;\n";
    $out .= "CREATE TABLE `$safeTable` (\n";

    $definitions = [];
    $primaryKeys = [];

    foreach ($columns as $column) {

        $name = str_replace('`', '``', $column['name']);
        $type = mysqlType($column['type']);

        $definition = "`$name` $type";

        if ((int)$column['notnull'] === 1) {
            $definition .= " NOT NULL";
        }

        if ((int)$column['pk'] === 1) {
            $primaryKeys[] = "`$name`";
        }

        $definitions[] = $definition;
    }

    if ($primaryKeys) {
        $definitions[] = "PRIMARY KEY (" . implode(', ', $primaryKeys) . ")";
    }

    $out .= "    " . implode(",\n    ", $definitions) . "\n";
    $out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

    $rows = $pdo->query(
        "SELECT * FROM `" . $safeTable . "`"
    )->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {

        $fields = [];
        $values = [];

        foreach ($row as $field => $value) {

            $fields[] = '`' . str_replace('`', '``', $field) . '`';

            if ($value === null) {
                $values[] = 'NULL';
            } else {
                $values[] = $pdo->quote($value);
            }
        }

        $out .= "INSERT INTO `$safeTable` (" .
            implode(', ', $fields) .
            ") VALUES (" .
            implode(', ', $values) .
            ");\n";
    }

    $out .= "\n";
}

$out .= "SET FOREIGN_KEY_CHECKS=1;\n";

file_put_contents($output, $out);

echo "MYSQL/MARIADB DUMP CREATED\n";
echo "Tables: " . count($tables) . "\n";
echo "File: " . $output . "\n";
