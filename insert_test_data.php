<?php
require_once 'config/database.php';

echo "<h1>Insert Test Data</h1>";

// Insert test criteria if not exists
try {
    $pdo->exec("
        INSERT IGNORE INTO criteria (code, name, description, type, weight, is_active) VALUES
        ('C1', 'Tingkat Kerugian', 'Besarnya kerugian materiil', 'benefit', 0.5748, TRUE),
        ('C2', 'Tingkat Dampak', 'Sejauh mana kasus berdampak', 'benefit', 0.2352, TRUE),
        ('C3', 'Urgensi Penanganan', 'Tingkat kepentingan penanganan', 'benefit', 0.1262, TRUE),
        ('C4', 'Ketersediaan Sumber Daya', 'Kesiapan personel dan fasilitas', 'benefit', 0.0638, TRUE)
    ");
    echo "<p style='color: green;'>✓ Criteria inserted</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error inserting criteria: " . $e->getMessage() . "</p>";
}

// Insert test TOPSIS analysis cases
try {
    $pdo->exec("
        INSERT IGNORE INTO topsis_analysis_cases (case_id, case_name, kerugian, korban, urgensi, penyebaran, created_by) VALUES
        ('TEST001', 'Test Case 1', 50000000, 3, 4, 3, 1),
        ('TEST002', 'Test Case 2', 75000000, 5, 5, 4, 1),
        ('TEST003', 'Test Case 3', 25000000, 2, 3, 2, 1)
    ");
    echo "<p style='color: green;'>✓ TOPSIS analysis cases inserted</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error inserting TOPSIS cases: " . $e->getMessage() . "</p>";
}

// Insert test AHP results
try {
    // Get criteria IDs
    $stmt = $pdo->query("SELECT id FROM criteria ORDER BY id LIMIT 4");
    $criteria = $stmt->fetchAll();

    if (count($criteria) >= 4) {
        $pdo->exec("DELETE FROM ahp_results WHERE created_by = 1");

        $stmt = $pdo->prepare("
            INSERT INTO ahp_results (session_id, criteria_id, weight, consistency_ratio, is_consistent, created_by)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $weights = [0.5748, 0.2352, 0.1262, 0.0638];
        for ($i = 0; $i < 4; $i++) {
            $stmt->execute(['test_session', $criteria[$i]['id'], $weights[$i], 0.05, 1, 1]);
        }
        echo "<p style='color: green;'>✓ AHP results inserted</p>";
    } else {
        echo "<p style='color: red;'>✗ Not enough criteria found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error inserting AHP results: " . $e->getMessage() . "</p>";
}

// Insert test TOPSIS calculations
try {
    $pdo->exec("DELETE FROM topsis_calculations WHERE calculated_by = 1");

    $stmt = $pdo->prepare("
        INSERT INTO topsis_calculations (session_id, case_id, positive_distance, negative_distance, closeness_coefficient, rank_position, calculated_by)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $testData = [
        ['TEST001', 0.3, 0.7, 0.7, 1, 1],
        ['TEST002', 0.2, 0.8, 0.8, 2, 1],
        ['TEST003', 0.4, 0.6, 0.6, 3, 1]
    ];

    foreach ($testData as $data) {
        $stmt->execute(['test_topsis_session', $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]]);
    }
    echo "<p style='color: green;'>✓ TOPSIS calculations inserted</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error inserting TOPSIS calculations: " . $e->getMessage() . "</p>";
}

echo "<p><a href='debug_results.php'>Check Results</a></p>";
echo "<p><a href='pages/results.php'>View Results Page</a></p>";
?>
