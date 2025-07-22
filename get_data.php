<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");

include_once 'cauhinh.php';

// Lấy tham số pagination từ EasyUI
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rows = isset($_GET['rows']) ? (int)$_GET['rows'] : 10;
$page = max(1, $page);
$rows = max(1, min(500, $rows)); // Giới hạn tối đa 500 rows
$offset = ($page - 1) * $rows;
$offset = max(0, $offset);

$table_sanpham = $config->dbTiento . "sanpham";
$table_sanpham_ngonngu = $config->dbTiento . "sanpham_ngonngu";

try {
    // Đếm tổng số records
    $count_sql = "SELECT COUNT(*) as total 
                  FROM $table_sanpham AS sanpham
                  INNER JOIN $table_sanpham_ngonngu AS sanpham_ngonngu
                  ON sanpham.id = sanpham_ngonngu.id";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->execute();
    $total = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Lấy dữ liệu với LIMIT và OFFSET dùng placeholder
    $sql = "SELECT sanpham.id AS id, 
                   sanpham.hash AS hash,
                   sanpham_ngonngu.ten AS ten,
                   sanpham_ngonngu.mota AS mota
            FROM $table_sanpham AS sanpham
            INNER JOIN $table_sanpham_ngonngu AS sanpham_ngonngu
            ON sanpham.id = sanpham_ngonngu.id
            LIMIT :rows OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':rows', $rows, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả về format JSON mà EasyUI DataGrid mong đợi
    $response = [
        'total' => (int)$total,
        'rows' => $data
    ];
    echo json_encode($response);
} catch (Exception $e) {
    // Trả về lỗi dưới dạng JSON
    echo json_encode([
        'total' => 0,
        'rows' => [],
        'error' => $e->getMessage()
    ]);
}

?>