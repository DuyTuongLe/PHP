<?php
include 'cauhinh.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Product Menu</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div id="result-container">
    <h2>Danh sách sản phẩm:</h2>
    <div id="error-message" class="error" style="display: none;"></div>
    <ul id="product-list"></ul>
</div>

<script>
$(document).ready(function(){
    $.getJSON('product_menu.php', function(data){
        console.log('Response:', data);
        
        if (data.error) {
            // Nếu có lỗi
            $('#error-message').text('Lỗi: ' + data.error).show();
            $('#product-list').hide();
        } else {
            // Nếu không có lỗi
            if (Array.isArray(data)) {
                // Nếu data là mảng
                if (data.length === 0) {
                    $('#product-list').append('<li class="error">Không có dữ liệu</li>');
                } else {
                    data.forEach(function(item){
                        $('#product-list').append(
                            '<li>' + 
                            '<strong>' + item.tieude + '</strong>' +
                            ' (ID: ' + item.danduong_id + ')' +
                            ' (Kieu: ' + item.kieu + ')' +
                            ' (Danh đường ID: ' + item.id + ')' +
                            ' (Goc ID: ' + item.goc_id + ')' +
                            '</li>'
                        );
                    });
                }
            } else {
                // Nếu data không phải là mảng
                $('#error-message').text('Lỗi: Dữ liệu không đúng định dạng mảng').show();
                $('#product-list').hide();
            }
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        $('#error-message').text('Lỗi kết nối: ' + textStatus + ' - ' + errorThrown).show();
        $('#product-list').hide();
    });
});
</script>

</body>
</html>