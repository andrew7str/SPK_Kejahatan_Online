<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pages/ahp.php');
    exit();
}

class AHP {
    private $criteria = ['C1', 'C2', 'C3', 'C4']; // Kerugian, Korban, Urgensi, Penyebaran
    private $matrix = [];
    private $weights = [];
    private $consistencyRatio = 0;
    
    // Random Index untuk uji konsistensi
    private $randomIndex = [
        1 => 0, 2 => 0, 3 => 0.58, 4 => 0.90, 5 => 1.12, 
        6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45
    ];
    
    public function __construct($pairwiseComparisons) {
        $this->buildMatrix($pairwiseComparisons);
        $this->calculateWeights();
        $this->calculateConsistency();
    }
    
    private function buildMatrix($comparisons) {
        // Inisialisasi matriks 4x4
        $this->matrix = array_fill(0, 4, array_fill(0, 4, 1));
        
        // Isi matriks berdasarkan perbandingan berpasangan
        $this->matrix[0][1] = floatval($comparisons['c1_c2']); // C1 vs C2
        $this->matrix[0][2] = floatval($comparisons['c1_c3']); // C1 vs C3
        $this->matrix[0][3] = floatval($comparisons['c1_c4']); // C1 vs C4
        $this->matrix[1][2] = floatval($comparisons['c2_c3']); // C2 vs C3
        $this->matrix[1][3] = floatval($comparisons['c2_c4']); // C2 vs C4
        $this->matrix[2][3] = floatval($comparisons['c3_c4']); // C3 vs C4
        
        // Isi bagian bawah matriks (reciprocal)
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                if ($i > $j) {
                    $this->matrix[$i][$j] = 1 / $this->matrix[$j][$i];
                }
            }
        }
    }
    
    private function calculateWeights() {
        // Hitung total setiap kolom
        $columnSums = array_fill(0, 4, 0);
        for ($j = 0; $j < 4; $j++) {
            for ($i = 0; $i < 4; $i++) {
                $columnSums[$j] += $this->matrix[$i][$j];
            }
        }
        
        // Normalisasi matriks
        $normalizedMatrix = [];
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $normalizedMatrix[$i][$j] = $this->matrix[$i][$j] / $columnSums[$j];
            }
        }
        
        // Hitung bobot (rata-rata baris)
        for ($i = 0; $i < 4; $i++) {
            $rowSum = 0;
            for ($j = 0; $j < 4; $j++) {
                $rowSum += $normalizedMatrix[$i][$j];
            }
            $this->weights[$i] = $rowSum / 4;
        }
    }
    
    private function calculateConsistency() {
        // Hitung λ max
        $lambdaMax = 0;
        for ($i = 0; $i < 4; $i++) {
            $sum = 0;
            for ($j = 0; $j < 4; $j++) {
                $sum += $this->matrix[$i][$j] * $this->weights[$j];
            }
            $lambdaMax += $sum / $this->weights[$i];
        }
        $lambdaMax = $lambdaMax / 4;
        
        // Hitung CI (Consistency Index)
        $ci = ($lambdaMax - 4) / (4 - 1);
        
        // Hitung CR (Consistency Ratio)
        $ri = $this->randomIndex[4];
        $this->consistencyRatio = $ci / $ri;
    }
    
    public function getWeights() {
        return $this->weights;
    }
    
    public function getConsistencyRatio() {
        return $this->consistencyRatio;
    }
    
    public function isConsistent() {
        return $this->consistencyRatio <= 0.10;
    }
    
    public function getMatrix() {
        return $this->matrix;
    }
}

try {
    // Ambil data dari form
    $comparisons = [
        'c1_c2' => $_POST['c1_c2'],
        'c1_c3' => $_POST['c1_c3'],
        'c1_c4' => $_POST['c1_c4'],
        'c2_c3' => $_POST['c2_c3'],
        'c2_c4' => $_POST['c2_c4'],
        'c3_c4' => $_POST['c3_c4']
    ];
    
    // Validasi input
    foreach ($comparisons as $key => $value) {
        if (empty($value) || !is_numeric($value)) {
            throw new Exception("Nilai perbandingan $key tidak valid");
        }
    }
    
    // Proses AHP
    $ahp = new AHP($comparisons);
    $weights = $ahp->getWeights();
    $cr = $ahp->getConsistencyRatio();
    $matrix = $ahp->getMatrix();
    
    // Simpan hasil ke database
    try {
        // Hapus hasil AHP sebelumnya
        $stmt = $pdo->prepare("DELETE FROM ahp_results WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        // Simpan hasil baru
        $stmt = $pdo->prepare("
            INSERT INTO ahp_results (user_id, criteria_name, weight, consistency_ratio, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        $criteriaNames = ['Tingkat Kerugian', 'Jumlah Korban', 'Urgensi', 'Potensi Penyebaran'];
        
        for ($i = 0; $i < 4; $i++) {
            $stmt->execute([
                $_SESSION['user_id'],
                $criteriaNames[$i],
                $weights[$i],
                $cr
            ]);
        }
        
        // Simpan matriks perbandingan
        $stmt = $pdo->prepare("DELETE FROM ahp_matrix WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        $stmt = $pdo->prepare("
            INSERT INTO ahp_matrix (user_id, row_criteria, col_criteria, value, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $stmt->execute([
                    $_SESSION['user_id'],
                    $i,
                    $j,
                    $matrix[$i][$j]
                ]);
            }
        }
        
    } catch (PDOException $e) {
        // Jika tabel belum ada, buat tabel
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS ahp_results (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                criteria_name VARCHAR(100) NOT NULL,
                weight DECIMAL(10,8) NOT NULL,
                consistency_ratio DECIMAL(10,8) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS ahp_matrix (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                row_criteria INT NOT NULL,
                col_criteria INT NOT NULL,
                value DECIMAL(10,8) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Coba simpan lagi
        $stmt = $pdo->prepare("
            INSERT INTO ahp_results (user_id, criteria_name, weight, consistency_ratio, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        for ($i = 0; $i < 4; $i++) {
            $stmt->execute([
                $_SESSION['user_id'],
                $criteriaNames[$i],
                $weights[$i],
                $cr
            ]);
        }
    }
    
    // Prepare response
    $response = [
        'success' => true,
        'weights' => $weights,
        'criteriaNames' => $criteriaNames,
        'consistencyRatio' => $cr,
        'isConsistent' => $ahp->isConsistent(),
        'matrix' => $matrix
    ];
    
    $_SESSION['success'] = 'Perhitungan AHP berhasil! CR = ' . number_format($cr, 4) . 
                          ($ahp->isConsistent() ? ' (Konsisten)' : ' (Tidak Konsisten)');
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

// Redirect kembali ke halaman AHP
header('Location: pages/ahp.php');
exit();
?>
