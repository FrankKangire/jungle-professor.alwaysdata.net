<?php
include "connection.php";

$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(255) UNIQUE NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "documents" => "CREATE TABLE IF NOT EXISTS documents (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        document_name VARCHAR(255) NOT NULL,
        document_path TEXT NOT NULL,
        upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "results" => "CREATE TABLE IF NOT EXISTS permanent_results (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        question_text TEXT NOT NULL,
        user_answer VARCHAR(255),
        correct_answer VARCHAR(255),
        is_correct BOOLEAN,
        game_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

echo "<h2>Initializing Database Tables...</h2>";

foreach ($tables as $name => $sql) {
    $result = pg_query($conn, $sql);
    if ($result) {
        echo "<p style='color:green;'>✔️ Table '$name' created or already exists.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error creating '$name': " . pg_last_error($conn) . "</p>";
    }
}
?>
