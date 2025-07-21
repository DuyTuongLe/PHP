<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
class config {
    var $dbmaychu = 'localhost';
    var $dbTen = 'tuongduy';
    var $dbUser = 'root';
    var $dbPassword = 'Oracle123duy@@#';
    var $hethongbaotri = 'Website đang được xây dụng, xin quay lại sau.';
    var $dbTiento = "hqv_";

}

$dulieu = $_REQUEST;

$config = new config();

try {
    $conn = new PDO(
        "mysql:host={$config->dbmaychu};dbname={$config->dbTen};charset=utf8",
        $config->dbUser,
        $config->dbPassword
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $err) {
    echo "Kết nối thất bại: " . $err->getMessage();
}
?>

vilinh
