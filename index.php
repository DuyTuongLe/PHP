<?php
include 'cauhinh.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EasyUI Layout Example</title>
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/f58aq50esd4eybebjtgd7itaxfmdd27dt7h1i8dgddtx1tt2/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
</head>

<body class="easyui-layout">

    <!-- Sidebar -->
    <div data-options="region:'west',split:true" style="width:200px;">
        <div class="easyui-accordion" data-options="fit:true,border:false">
            <div title="Title1" data-options="iconCls:'icon-save'">
                content1
            </div>
            <div title="Title2" data-options="iconCls:'icon-save'">
                content2
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div data-options="region:'center'" class="easyui-layout">
        <div data-options="region:'west',split:true" style="width:200px;" class="khungbentrai">
            
        </div>
        <div data-options="region:'center'">
            <table id="dg" class="easyui-datagrid">
                <thead>
                    <tr>
                        <th field="id" width="50">ID</th>
                        <th field="hash" width="150">Tên</th>
                        <th field="ten" width="300">Thông tin</th>
                        <th field="mota" width="300">Mô tả</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <script>
    $(function(){
        let editIndex = undefined;
        $('#dg').datagrid({
            title: 'Danh sách sản phẩm',
            url: 'get_data.php',
            pagination: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10, 20, 50, 100, 200, 500],
            remoteSort: false,
            method: 'get',
            queryParams: {},
            onLoadSuccess: function(data) {
                console.log('Data loaded:', data);
            },
            onLoadError: function() {
                console.log('Error loading data');
                $.messager.alert('Lỗi', 'Không thể tải dữ liệu!', 'error');
            },
            columns:[[
                { field: 'ck', checkbox: true },
                {
                    field:'id', 
                    title:'Mã', 
                    width:50,
                    editor: {
                        type: 'textbox',
                        options: {
                            multiline: true,
                            height: 100
                        }
                    }
                },
                {
                    field:'ten',
                    title:'Tên thiết bị',
                    width:200,
                    editor: {
                        type: 'textbox',
                        options: {
                            multiline: true,
                            height: 100
                        }
                    },
                    formatter: function(value, row, index) {
                        return `
                                <div style="max-height:100px;overflow:hidden;">
                                    ${value}
                                </div>
                        `;
                    }
                },
                {
                    field: 'hash', 
                    title: 'Hash', 
                    width: 200, 
                    editor: {
                        type: 'textbox',
                        options: {
                            multiline: true,
                            height: 100
                        }
                    },
                    formatter: function(value, row, index) {
                        return `
                                <div style="max-height:100px;overflow:hidden;">
                                    ${value}
                                </div>
                        `;
                    }
                },
                {
                    field:'mota',
                    title:'Mô tả',
                    width:200,
                    editor: {
                        type: 'textbox',
                        options: {
                            multiline: true,
                            height: 100,
                            icons: [{
                                iconCls: 'icon-edit',
                                handler: function(e){
                                    const inputVal = $(e.data.target).textbox('getValue');
                                    openTinyEditor(e.data.target, inputVal);
                                }
                            }]
                        }
                    },
                    formatter: function(value, row, index) {
                        return `
                            <div style="max-height:100px;overflow:hidden;">
                                ${value}
                            </div>
                        `;
                    }
                }
            ]],
            onClickCell: function(index, field, value) {
                if (field === 'ck') return;

                if (editIndex !== undefined) {
                    $('#dg').datagrid('endEdit', editIndex);
                }
                $('#dg').datagrid('selectRow', index).datagrid('beginEdit', index);
                editIndex = index;

                // Focus ngay vào ô vừa click
                var ed = $('#dg').datagrid('getEditor', { index: index, field: field });
                if (ed) {
                    $(ed.target).focus();
                }
            },
            toolbar: [
                {
                    text: 'Reload',
                    iconCls: 'icon-reload',
                    handler: function(){ reloadGrid(); }
                },
                '-',
                {
                    text: 'Add (F2)',
                    iconCls: 'icon-add',
                    handler: function(){ addItem(); }
                },
                {
                    text: 'Save (F3)',
                    iconCls: 'icon-save',
                    handler: function(){ saveItem(); }
                },
                {
                    text: 'Reject (ESC)',
                    iconCls: 'icon-undo',
                    handler: function(){ rejectChanges(); }
                },
                '-',
                {
                    text: 'Edit (Ctrl+Alt+E)',
                    iconCls: 'icon-edit',
                    handler: function(){ editItem(); }
                },
                '-',
                {
                    text: 'Publish',
                    iconCls: 'icon-ok',
                    handler: function(){ publishItem(); }
                },
                {
                    text: 'unPublish',
                    iconCls: 'icon-cancel',
                    handler: function(){ unpublishItem(); }
                },
                {
                    text: 'Remove',
                    iconCls: 'icon-remove',
                    handler: function(){ removeItem(); }
                },
                {
                    text: 'Import',
                    iconCls: 'icon-import-export',
                    handler: function(){ importItem(); }
                }
            ]
        });
        $(document).keydown(function(e) {
            if (e.keyCode == 113) { // F2 - Add
                e.preventDefault();
                addItem();
            } else if (e.keyCode == 114) { // F3 - Save
                e.preventDefault();
                saveItem();
            } else if (e.keyCode == 27) { // ESC - Reject
                e.preventDefault();
                rejectChanges();
            } else if (e.ctrlKey && e.altKey && e.keyCode == 69) { // Ctrl+Alt+E - Edit
                e.preventDefault();
                editItem();
            }
        });
        setTimeout(function() {
            $('body').layout('resize');
        }, 100);
    });

    function openTinyEditor(target, currentContent){
    if ($('#tinyWindow').length === 0) {
        $('body').append(`
            <div id="tinyWindow" style="width:640px;height:500px;">
                <textarea id="tinyEditorArea" style="width:100%;height:100%;"></textarea>
            </div>
        `);
    }

    $('#tinyWindow').dialog({
        title: 'Soạn thảo nội dung',
        modal: true,
        closed: true,
        buttons: [
            {
                text: 'Update',
                iconCls: 'icon-save',
                handler: function(){
                    const content = tinymce.get('tinyEditorArea').getContent();
                    $(target).textbox('setValue', content);
                    closeTinyWindow();
                }
            },
            {
                text: 'Cancel',
                iconCls: 'icon-cancel',
                handler: function(){
                    closeTinyWindow();
                }
            }
        ],
        onOpen: function(){
            tinymce.init({
                selector: '#tinyEditorArea',
                height: 400,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons', 'accordion'
                ],
                toolbar: 'undo redo | removeformat | link image table | blocks fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | forecolor backcolor | outdent indent | numlist bullist | anchor | paste accordion | charmap emoticons | fullscreen code',
                toolbar_mode: 'wrap',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                setup: function(editor){
                    editor.on('init', function(){
                        editor.setContent(currentContent || '');
                    });
                }
            });
        },
        onClose: function(){
            if (tinymce.get('tinyEditorArea')) {
                tinymce.get('tinyEditorArea').remove();
            }
        }
    }).dialog('open');
}

    function closeTinyWindow(){
        if (tinymce.get('tinyEditorArea')) {
            tinymce.get('tinyEditorArea').remove();
        }
        $('#tinyWindow').window('close');
    }



    function reloadGrid() { $('#dg').datagrid('reload'); }
    function addItem() {
        $('#dg').datagrid('insertRow', {
            index: 0, 
            row: {
                id: '',
                hash: '',
                ten: ''
            }
        });

        $('#dg').datagrid('beginEdit', 0); // Bắt đầu edit dòng đầu
    }
    function saveItem() {
        var rows = $('#dg').datagrid('getChanges');
        if (rows.length) {
            $('#dg').datagrid('acceptChanges');

            // Gửi AJAX lên server để lưu
            $.ajax({
                url: 'save_data.php',
                method: 'POST',
                data: {data: JSON.stringify(rows)},
                success: function(response) {
                    console.log('Server response:', response);
                    $.messager.alert('Thành công', 'Đã lưu dữ liệu');
                    $('#dg').datagrid('reload');
                },
                error: function() {
                    $.messager.alert('Lỗi', 'Không thể lưu dữ liệu', 'error');
                }
            });
        } else {
            $.messager.alert('Thông báo', 'Không có thay đổi nào để lưu');
        }
    }
    function rejectChanges() { $('#dg').datagrid('rejectChanges'); }
    function editItem() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            var index = $('#dg').datagrid('getRowIndex', row);
            $('#dg').datagrid('beginEdit', index);
        } else {
            $.messager.alert('Thông báo', 'Vui lòng chọn dòng cần sửa.');
        }
    }
    function publishItem() { }
    function unpublishItem() { }
    function removeItem() { /* code xóa */
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            var rowIndex = $('#dg').datagrid('getRowIndex', row);
            $('#dg').datagrid('deleteRow', rowIndex);
        } else {
            $.messager.alert('Thông báo', 'Vui lòng chọn dòng cần xóa.');
        }
    }
    function importItem() { /* code import */ }

    </script>
</body>
</html>
