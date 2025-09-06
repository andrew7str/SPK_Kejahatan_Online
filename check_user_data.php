<?php
require_once 'config/database.php';

try {
    echo "Checking user data...\n";

    // Check cases for user ID 3
    $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM topsis_analysis_cases WHERE created_by = ?');
    $stmt->execute([3]);
    $count = $stmt->fetch()['count'];
    echo 'User ID 3 cases: ' . $count . "\n";

    // Check all cases
    $stmt = $pdo->query('SELECT created_by, case_id, case_name FROM topsis_analysis_cases');
    $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo 'All cases:' . "\n";
    foreach ($cases as $case) {
        echo '- User ' . $case['created_by'] . ': ' . $case['case_id'] . ' - ' . $case['case_name'] . "\n";
    }

    // Check AHP results for user ID 3
    $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM ahp_results WHERE created_by = ?');
    $stmt->execute([3]);
    $ahpCount = $stmt->fetch()['count'];
    echo 'User ID 3 AHP results: ' . $ahpCount . "\n";

    // If user 3 has no cases, add some sample cases for them
    if ($count == 0) {
        echo "Adding sample cases for user ID 3...\n";
        $pdo->exec("
            INSERT INTO topsis_analysis_cases
            (case_id, case_name, kerugian, korban, urgensi, penyebaran, created_by)
            VALUES
            ('KJO-2025-001', 'Penipuan Online Marketplace', 49944304, 3, 4, 3, 3),
            ('KJO-2025-002', 'Investasi Bodong Online', 55000000, 4, 5, 3, 3),
            ('KJO-2025-003', 'Phishing Banking', 63000000, 3, 3, 2, 3)
        ");
        echo "Sample cases added for user ID 3\n";
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
