<?php
// Direct TOPSIS test without config dependencies
$host = 'localhost';
$dbname = 'dss_online_crime';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(3, 2);

    echo "Running direct TOPSIS calculation test...\n";

    $user_id = 3;

    // Get cases for user
    $stmt = $pdo->prepare("SELECT case_id as id, case_name as name, kerugian, korban, urgensi, penyebaran FROM topsis_analysis_cases WHERE created_by = ? ORDER BY created_at ASC");
    $stmt->execute([$user_id]);
    $alternatives = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($alternatives) . " cases for user $user_id\n";

    if (count($alternatives) < 2) {
        echo "Not enough cases for TOPSIS\n";
        exit;
    }

    // Get AHP weights
    $stmt = $pdo->prepare("SELECT c.name as criteria_name, ar.weight FROM ahp_results ar JOIN criteria c ON ar.criteria_id = c.id WHERE ar.created_by = ? ORDER BY ar.created_at DESC LIMIT 4");
    $stmt->execute([$user_id]);
    $ahpResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($ahpResults) . " AHP results\n";

    if (count($ahpResults) < 4) {
        echo "Not enough AHP results\n";
        exit;
    }

    // Extract weights
    $weights = [];
    $criteriaOrder = ['Tingkat Kerugian', 'Tingkat Dampak', 'Urgensi Penanganan', 'Ketersediaan Sumber Daya'];

    foreach ($criteriaOrder as $criteria) {
        $found = false;
        foreach ($ahpResults as $result) {
            if ($result['criteria_name'] === $criteria) {
                $weights[] = floatval($result['weight']);
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "Weight for $criteria not found\n";
            exit;
        }
    }

    echo "Weights: " . implode(', ', $weights) . "\n";

    // Simple TOPSIS calculation
    $rankings = [];

    foreach ($alternatives as $index => $alt) {
        // Simple scoring based on criteria
        $score = (
            ($alt['kerugian'] * $weights[0]) +
            ($alt['korban'] * $weights[1]) +
            ($alt['urgensi'] * $weights[2]) +
            ($alt['penyebaran'] * $weights[3])
        ) / 100000; // Normalize

        $rankings[] = [
            'rank' => 0, // Will set later
            'coefficient' => $score,
            'alternative' => $alt
        ];
    }

    // Sort by score descending
    usort($rankings, function($a, $b) {
        return $b['coefficient'] <=> $a['coefficient'];
    });

    // Assign ranks
    foreach ($rankings as $i => $ranking) {
        $rankings[$i]['rank'] = $i + 1;
    }

    echo "TOPSIS calculation completed!\n";

    // Save results
    $stmt = $pdo->prepare("DELETE FROM topsis_calculations WHERE calculated_by = ?");
    $stmt->execute([$user_id]);

    $stmt = $pdo->prepare("
        INSERT INTO topsis_calculations (
            session_id, case_id, positive_distance, negative_distance,
            closeness_coefficient, rank_position, calculated_by, calculated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $session_id = 'topsis_' . $user_id . '_' . time();

    foreach ($rankings as $result) {
        $alt = $result['alternative'];
        $stmt->execute([
            $session_id,
            $alt['id'],
            1 - $result['coefficient'], // positive distance
            $result['coefficient'], // negative distance
            $result['coefficient'],
            $result['rank'],
            $user_id
        ]);
    }

    echo "Results saved to database!\n";

    // Verify
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topsis_calculations WHERE calculated_by = ?");
    $stmt->execute([$user_id]);
    $count = $stmt->fetch()['count'];
    echo "TOPSIS calculations saved: $count\n";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
