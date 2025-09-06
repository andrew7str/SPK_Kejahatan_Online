<?php
// Test TOPSIS calculation for user ID 3
session_start();
$_SESSION['user_id'] = 3; // Simulate user ID 3

require_once 'config/database.php';

try {
    echo "Testing TOPSIS calculation for user ID 3...\n";

    // Check if we have cases
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topsis_analysis_cases WHERE created_by = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $caseCount = $stmt->fetch()['count'];
    echo "Cases found: $caseCount\n";

    // Check AHP results
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM ahp_results WHERE created_by = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $ahpCount = $stmt->fetch()['count'];
    echo "AHP results found: $ahpCount\n";

    if ($caseCount >= 2 && $ahpCount >= 4) {
        echo "Requirements met, running TOPSIS calculation...\n";

        // Simulate POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Include and run the TOPSIS process
        include 'process_topsis.php';

        echo "TOPSIS calculation completed!\n";

        // Check if calculations were saved
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topsis_calculations WHERE calculated_by = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $calcCount = $stmt->fetch()['count'];
        echo "TOPSIS calculations saved: $calcCount\n";

    } else {
        echo "Requirements not met for TOPSIS calculation\n";
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
