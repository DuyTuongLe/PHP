<?php

include_once 'cauhinh.php';

$table_danduong = $config->dbTiento . "danduong";
$table_danduong_ngonngu = $config->dbTiento . "danduong_ngonngu";

function getDanhSachDanDuong($conn) {
    global $table_danduong, $table_danduong_ngonngu;
    $sql = "SELECT danduong.danduong_id, danduong.kieu, danduong.goc_id, danduongnn.tieude
            FROM $table_danduong AS danduong
            INNER JOIN $table_danduong_ngonngu AS danduongnn ON danduong.danduong_id = danduongnn.id
            WHERE danduong.kieu = 'product'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function buildTree($elements, $parentId = 0) {
    $branch = [];

    foreach ($elements as $element) {
        if ($element['goc_id'] == $parentId) {
            $children = buildTree($elements, $element['danduong_id']); // đệ quy
            $node = [
                'id' => $element['danduong_id'], 
                'text' => $element['tieude']
            ];
            if ($children) {
                $node['children'] = $children;
            }
            $branch[] = $node;
        }
    }

    return $branch;
}

$danduongList = getDanhSachDanDuong($conn);

// Xây cây bắt đầu từ node gốc (ví dụ: id = 3)
$tree = buildTree($danduongList, 0);

?>

    