<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pages/topsis.php');
    exit();
}

class TOPSIS {
    private $alternatives = [];
    private $criteria = [];
    private $weights = [];
    private $matrix = [];
    private $normalizedMatrix = [];
    private $weightedMatrix = [];
    private $idealPositive = [];
    private $idealNegative = [];
    private $distances = [];
    private $closenessCoefficients = [];
    private $rankings = [];
    
    public function __construct($alternatives, $weights) {
        $this->alternatives = $alternatives;
        $this->weights = $weights;
        $this->buildMatrix();
        $this->normalizeMatrix();
        $this->calculateWeightedMatrix();
        $this->findIdealSolutions();
        $this->calculateDistances();
        $this->calculateClosenessCoefficients();
        $this->calculateRankings();
    }
    
    private function buildMatrix() {
        // Bangun matriks keputusan dari alternatif
        foreach ($this->alternatives as $index => $alt) {
            $this->matrix[$index] = [
                floatval($alt['kerugian']),
                floatval($alt['korban']),
                floatval($alt['urgensi']),
                floatval($alt['penyebaran'])
            ];
        }
    }
    
    private function normalizeMatrix() {
        $numCriteria = 4;
        $numAlternatives = count($this->alternatives);
        
        // Hitung akar kuadrat dari jumlah kuadrat setiap kolom
        $columnSums = array_fill(0, $numCriteria, 0);
        
        for ($j = 0; $j < $numCriteria; $j++) {
            for ($i = 0; $i < $numAlternatives; $i++) {
                $columnSums[$j] += pow($this->matrix[$i][$j], 2);
            }
            $columnSums[$j] = sqrt($columnSums[$j]);
        }
        
        // Normalisasi setiap elemen
        for ($i = 0; $i < $numAlternatives; $i++) {
            for ($j = 0; $j < $numCriteria; $j++) {
                $this->normalizedMatrix[$i][$j] = $this->matrix[$i][$j] / $columnSums[$j];
            }
        }
    }
    
    private function calculateWeightedMatrix() {
        $numAlternatives = count($this->alternatives);
        
        // Kalikan matriks ternormalisasi dengan bobot
        for ($i = 0; $i < $numAlternatives; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $this->weightedMatrix[$i][$j] = $this->normalizedMatrix[$i][$j] * $this->weights[$j];
            }
        }
    }
    
    private function findIdealSolutions() {
        $numAlternatives = count($this->alternatives);
        
        // Untuk setiap kriteria, cari nilai maksimum (ideal positif) dan minimum (ideal negatif)
        // Semua kriteria diasumsikan benefit (semakin besar semakin baik)
        for ($j = 0; $j < 4; $j++) {
            $values = [];
            for ($i = 0; $i < $numAlternatives; $i++) {
                $values[] = $this->weightedMatrix[$i][$j];
            }
            
            $this->idealPositive[$j] = max($values);
            $this->idealNegative[$j] = min($values);
        }
    }
    
    private function calculateDistances() {
        $numAlternatives = count($this->alternatives);
        
        // Hitung jarak ke solusi ideal positif dan negatif
        for ($i = 0; $i < $numAlternatives; $i++) {
            $distancePositive = 0;
            $distanceNegative = 0;
            
            for ($j = 0; $j < 4; $j++) {
                $distancePositive += pow($this->weightedMatrix[$i][$j] - $this->idealPositive[$j], 2);
                $distanceNegative += pow($this->weightedMatrix[$i][$j] - $this->idealNegative[$j], 2);
            }
            
            $this->distances[$i] = [
                'positive' => sqrt($distancePositive),
                'negative' => sqrt($distanceNegative)
            ];
        }
    }
    
    private function calculateClosenessCoefficients() {
        $numAlternatives = count($this->alternatives);
        
        // Hitung closeness coefficient
        for ($i = 0; $i < $numAlternatives; $i++) {
            $dPositive = $this->distances[$i]['positive'];
            $dNegative = $this->distances[$i]['negative'];
            
            if (($dPositive + $dNegative) == 0) {
                $this->closenessCoefficients[$i] = 0;
            } else {
                $this->closenessCoefficients[$i] = $dNegative / ($dPositive + $dNegative);
            }
        }
    }
    
    private function calculateRankings() {
        // Buat array dengan index dan closeness coefficient
        $rankings = [];
        for ($i = 0; $i < count($this->alternatives); $i++) {
            $rankings[] = [
                'index' => $i,
                'coefficient' => $this->closenessCoefficients[$i],
                'alternative' => $this->alternatives[$i]
            ];
        }
        
        // Urutkan berdasarkan closeness coefficient (descending)
        usort($rankings, function($a, $b) {
            return $b['coefficient'] <=> $a['coefficient'];
        });
        
        // Assign ranking
        foreach ($rankings as $rank => $item) {
            $this->rankings[] = [
                'rank' => $rank + 1,
                'index' => $item['index'],
                'coefficient' => $item['coefficient'],
                'alternative' => $item['alternative']
            ];
        }
    }
    
    public function getRankings() {
        return $this->rankings;
    }
    
    public function getMatrix() {
        return $this->matrix;
    }
    
    public function getNormalizedMatrix() {
        return $this->normalizedMatrix;
    }
    
    public function getWeightedMatrix() {
        return $this->weightedMatrix;
    }
    
    public function getIdealSolutions() {
        return [
            'positive' => $this->idealPositive,
            'negative' => $this->idealNegative
        ];
    }
    
    public function getDistances() {
        return $this->distances;
    }
    
    public function getClosenessCoefficients() {
        return $this->closenessCoefficients;
    }
}

try {
    // Ambil data alternatif dari POST
    $alternatives = [];
    if (isset($_POST['alternatives'])) {
        // Decode JSON string jika data dikirim sebagai JSON
        if (is_string($_POST['alternatives'])) {
            $alternatives = json_decode($_POST['alternatives'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Format data alternatif tidak valid: " . json_last_error_msg());
            }
        } elseif (is_array($_POST['alternatives'])) {
            $alternatives = $_POST['alternatives'];
        } else {
            throw new Exception("Data alternatif tidak valid");
        }
    } else {
        throw new Exception("Data alternatif tidak ditemukan");
    }
    
    // Validasi minimal 2 alternatif
    if (count($alternatives) < 2) {
        throw new Exception("Minimal diperlukan 2 alternatif untuk perhitungan TOPSIS");
    }
    
    // Ambil bobot dari hasil AHP terbaru
    $stmt = $pdo->prepare("
        SELECT criteria_name, weight 
        FROM ahp_results 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 4
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $ahpResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($ahpResults) < 4) {
        throw new Exception("Bobot kriteria AHP belum tersedia. Silakan lakukan perhitungan AHP terlebih dahulu.");
    }
    
    // Susun bobot sesuai urutan kriteria
    $weights = [];
    $criteriaOrder = ['Tingkat Kerugian', 'Jumlah Korban', 'Urgensi', 'Potensi Penyebaran'];
    
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
            throw new Exception("Bobot untuk kriteria '$criteria' tidak ditemukan");
        }
    }
    
    // Proses TOPSIS
    $topsis = new TOPSIS($alternatives, $weights);
    $rankings = $topsis->getRankings();
    
    // Simpan hasil ke database
    try {
        // Hapus hasil TOPSIS sebelumnya
        $stmt = $pdo->prepare("DELETE FROM topsis_results WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        // Simpan hasil baru
        $stmt = $pdo->prepare("
            INSERT INTO topsis_results (
                user_id, alternative_id, alternative_name, kerugian, korban, 
                urgensi, penyebaran, closeness_coefficient, ranking, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        foreach ($rankings as $result) {
            $alt = $result['alternative'];
            $stmt->execute([
                $_SESSION['user_id'],
                $alt['id'],
                $alt['name'],
                $alt['kerugian'],
                $alt['korban'],
                $alt['urgensi'],
                $alt['penyebaran'],
                $result['coefficient'],
                $result['rank']
            ]);
        }
        
    } catch (PDOException $e) {
        // Jika tabel belum ada, buat tabel
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS topsis_results (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                alternative_id VARCHAR(50) NOT NULL,
                alternative_name VARCHAR(255) NOT NULL,
                kerugian BIGINT NOT NULL,
                korban INT NOT NULL,
                urgensi INT NOT NULL,
                penyebaran INT NOT NULL,
                closeness_coefficient DECIMAL(10,8) NOT NULL,
                ranking INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Coba simpan lagi
        foreach ($rankings as $result) {
            $alt = $result['alternative'];
            $stmt->execute([
                $_SESSION['user_id'],
                $alt['id'],
                $alt['name'],
                $alt['kerugian'],
                $alt['korban'],
                $alt['urgensi'],
                $alt['penyebaran'],
                $result['coefficient'],
                $result['rank']
            ]);
        }
    }
    
    $_SESSION['success'] = 'Perhitungan TOPSIS berhasil! ' . count($alternatives) . ' alternatif telah dianalisis.';
    
    // Simpan hasil untuk ditampilkan
    $_SESSION['topsis_results'] = $rankings;
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    
    // Return JSON response for AJAX requests
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Check if this is an AJAX request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Perhitungan TOPSIS berhasil!']);
    exit();
}

// Redirect kembali ke halaman TOPSIS untuk non-AJAX requests
header('Location: pages/topsis.php');
exit();
?>
