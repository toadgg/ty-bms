<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 2'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap -->
    <!-- Font Awesome -->
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ mix('/css/base.css') }}">

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/media/css/dataTables.bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/Responsive/css/responsive.bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/Scroller/css/scroller.bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
    @endif

    @yield('adminlte_css')

    <!-- adminlte -->
    <link rel="stylesheet" href="{{ mix('/css/adminlte.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ mix('/css/skins/skin-' . config('adminlte.skin', 'blue') . '.css')}} ">

    <!-- 工程样式 -->
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')

<script src="{{ mix('/js/app.js') }}"></script>

@if(config('adminlte.plugins.datatables'))
    <!-- DataTables -->
    <script src="{{ asset('/adminlte/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/media/js/dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>

    <!-- 横向滚动条 -->
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') }}"></script>

    <!-- 工具栏按钮 -->
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/vendor/jszip.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/vendor/pdfmake.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/vendor/vfs_fonts.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Select/js/dataTables.select.min.js') }}"></script>

    <!-- data table通用配置 -->
    <script type="text/javascript">
        if ($('.nprogress').length > 0) {
            NProgress.start();
        }
        pdfMake.fonts  = {
            Roboto: {
                normal: 'msyh.ttf',
                bold: 'msyh.ttf',
                italics: 'msyh.ttf',
                bolditalics: 'msyh.ttf'
            }
        };
        $.extend(true, $.fn.dataTable.defaults, {
            order: [[ 0, "desc" ]],
            processing : true,
            deferRender:    true,
            scrollY:        400,
            scrollX:        true,
            scrollCollapse: true,
            lengthChange: false,
            info: false,
            dom: "Bfrtip",
            initComplete: function () {
                NProgress.done();
            },
            columnDefs: [{
                targets: 0,//第一列隐藏
                visible: false,
                searchable: false
            }],
            language: { //国际化配置
                processing: "正在获取数据，请稍后...",
                lengthMenu: "显示 _MENU_ 条",
                zeroRecords: "没有您要搜索的内容",
                info: "从 _START_ 到  _END_ 条记录 总记录数为 _TOTAL_ 条",
                infoEmpty: "记录数为0",
                infoFiltered: "(全部记录数 _MAX_ 条)",
                infoPostFix: "",
                search: "搜索",
                url: "",
                paginate: {
                    first: "第一页",
                    previous: "上一页",
                    next: "下一页",
                    last: "最后一页"
                }
            },
            buttons: [
                {
                    extend: 'copy',
                    text: '',
                    className: 'fa fa-copy'
                },
                {
                    extend: 'excel',
                    text: '',
                    className: 'fa fa-file-excel-o'
                },
                {
                    extend: 'pdf',
                    text: '',
                    className: 'fa fa-file-pdf-o',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'print',
                    text: '',
                    className: 'fa fa-print',
                    customize: function (win) {
                        $(win.document.body).css('font-size', '10pt');
                        $(win.document.body)
                            .find('table')
                            .removeClass('nowrap')
                            .css('font-size', 'inherit');
                    }
                }
            ]
        });
        $("#tableShadow").replaceWith($("#tableShadow table").attr("id","tyTable"));
    </script>
@endif

@yield('adminlte_js')

</body>
</html>
