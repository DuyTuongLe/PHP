<h2>Danh sách Dẫn Đường</h2>

<ul id="danduongTree"></ul>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="https://www.jeasyui.com/easyui/themes/default/easyui.css">

<script>
$('#danduongTree').tree({
    url: 'product_menu.php',
    method: 'get',
    animate: true
});
</script>