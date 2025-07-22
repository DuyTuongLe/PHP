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

// Lấy dữ liệu
$danduongList = getDanhSachDanDuong($conn);
?>

<h2>Danh sách Dẫn Đường</h2>
<table id="dg" class="easyui-datagrid">
    <thead>
         <tr>
            <th field="id" width="50">ID</th>
            <th field="hash" width="150">Tieuu đề</th>
            <th field="ten" width="300">Kiểu</th>
            <th field="mota" width="300">Gốc id</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($danduongList as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['danduong_id']); ?></td>
                <td><?php echo htmlspecialchars($item['tieude']); ?></td>
                <td><?php echo htmlspecialchars($item['kieu']); ?></td>
                <td><?php echo htmlspecialchars($item['goc_id']); ?></td>
            </tr>
        <?php endforeach; ?>
</table>