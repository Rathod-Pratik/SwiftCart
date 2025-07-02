<?php
function checkAndCreateTable(PDO $pdo, string $tableName, string $createSQL, string $schema = 'public'): string
{
    $stmt = $pdo->prepare("
        SELECT EXISTS (
            SELECT 1 
            FROM information_schema.tables 
            WHERE table_schema = :schema 
              AND table_name = :table
        )
    ");
    $stmt->execute([
        ':schema' => $schema,
        ':table' => $tableName
    ]);

    $exists = $stmt->fetchColumn();

    if ($exists) {
        return "✅ Table `$tableName` already exists.";
    } else {
        $pdo->exec($createSQL);
        return "✅ Table `$tableName` created successfully.";
    }
}
?>
