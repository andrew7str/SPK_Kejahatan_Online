<?php
// Fix the foreign key issue in topsis_calculations table
$host = 'localhost';
$dbname = 'dss_online_crime';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(3, 2);

    echo "Fixing foreign key constraint...\n";

    // Drop the existing foreign key
    $pdo->exec("ALTER TABLE topsis_calculations DROP FOREIGN KEY topsis_calculations_ibfk_1");

    // Change case_id to VARCHAR to match topsis_analysis_cases
    $pdo->exec("ALTER TABLE topsis_calculations MODIFY case_id VARCHAR(50) NOT NULL");

    // Add new foreign key to topsis_analysis_cases
    $pdo->exec("ALTER TABLE topsis_calculations ADD CONSTRAINT fk_topsis_case FOREIGN KEY (case_id) REFERENCES topsis_analysis_cases(case_id) ON DELETE CASCADE");

    echo "Foreign key fixed!\n";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
