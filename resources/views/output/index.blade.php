@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>工作台</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header no-padding">
                <div class="mailbox-read-info">
                    <h3>{{ $year }}年项目产值申报</h3>
                </div>
                <div class="box-tools pull-right">
                    <a href="{{ route('outputs.index', [ 'y' => $year - 1]) }}" class="btn btn-box-tool " data-toggle="tooltip" title="" data-original-title="{{ $year - 1 }}年"><i class="fa fa-chevron-left">{{ $year - 1 }}年</i></a>
                    <a href="{{ route('outputs.index', [ 'y' => $year + 1]) }}" class="btn btn-box-tool @if($year - date('Y') == 0) disabled @endif" data-toggle="tooltip" title="" data-original-title="{{ $year + 1 }}年"><i class="fa">{{ $year + 1 }}年</i><i class="fa fa-chevron-right"></i></a>
                </div>
            </div>

            <div class="box-body nprogress">
                <div style="display:none;" >
                    <div id="outputForm">
                        <div class="row">
                            <editor-field name="project_id"></editor-field>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-success box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">第一季度</h3>
                                    </div>
                                    <div class="box-body">
                                        <editor-field name="m_1"></editor-field>
                                        <editor-field name="m_2"></editor-field>
                                        <editor-field name="m_3"></editor-field>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box box-danger box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">第二季度</h3>
                                    </div>
                                    <div class="box-body">
                                        <editor-field name="m_4"></editor-field>
                                        <editor-field name="m_5"></editor-field>
                                        <editor-field name="m_6"></editor-field>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-warning box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">第三季度</h3>
                                    </div>
                                    <div class="box-body">
                                        <editor-field name="m_7"></editor-field>
                                        <editor-field name="m_8"></editor-field>
                                        <editor-field name="m_9"></editor-field>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="box box-info box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">第四季度</h3>
                                    </div>
                                    <div class="box-body">
                                        <editor-field name="m_10"></editor-field>
                                        <editor-field name="m_11"></editor-field>
                                        <editor-field name="m_12"></editor-field>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tableShadow" class="table-warp">
                    @include('components.loading')
                    <div style="display:none;" >
                        <table class="table table-bordered table-striped dataTable nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>项目名称</th>
                                @for($i = 1; $i <= 12; $i++)
                                    <th data-class-name="currency">{{ $i }}月份</th>
                                @endfor
                                <th>操作</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/Editor/css/editor.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/FixedColumns/css/fixedColumns.bootstrap.min.css') }}">
    <style>
        .control-label.col-lg-4 {
            width: auto;
        }
        .control.col-lg-8 {
            width: auto;
        }
    </style>
@endpush

@push('js')

    <!-- InputMask -->
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.numeric.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/jquery.inputmask.min.js') }}"></script>

    <script src="{{ asset('/adminlte/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js') }}"></script>

    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Editor/js/dataTables.editor.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Editor/js/editor.bootstrap.min.js') }}"></script>


    <script>
        var editor;
        $(document).ready(function(){

            $.fn.dataTable.ext.errMode = 'none';
            $.fn.dataTable.defaults.buttons.unshift(
                {
                    text: '',
                    className: 'fa fa-plus',
                    action: function () {
                        editor.create( {
                            title: '新项目产值申报',
                            buttons: '创建'
                        })
                    }
                }
            );
            var table = $('#tyTable').DataTable({
                select: {style: 'os', items: 'cell', info: false},
                ajax: "{{ route('outputs.index', ['y' => $year]) }}",
                fixedColumns: {
                    leftColumns: 2,
                    rightColumns: 1
                },
                columns: [
                    { data: 'id', searchable: false },
                    { data: 'project', render: "name" },
                    { data: 'm_1', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_2', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_3', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_4', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_5', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_6', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_7', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_8', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_9', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_10', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_11', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: 'm_12', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' )},
                    { data: null, orderable: false, className: "center",
                        defaultContent: '<div class="">' +
                        '<a href="" class="editor_edit btn btn-xs btn-default"><span class="fa fa-fw fa-pencil"></span></a> ' +
                        '<a href="" class="editor_remove btn btn-xs btn-default"><span class="fa fa-fw fa-trash"></span></a>' +
                        '</div>'
                    },
                ]
            });

            editor = new $.fn.dataTable.Editor({
                ajax: {
                    url : "{{ route('outputs.store') }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                },
                table: '#tyTable',
                idSrc: 'id',
                template: '#outputForm',
                fields: [
                    { name: "year", type:"hidden", def: "{{ $year }}"},
                    {
                        label: "项目名称",
                        name:  "project_id",
                        type:  "select",
                        options: {!!  json_encode($projects) !!},
                        optionsPair: {
                            label: 'name',
                            value: 'id'
                        }},
                    { name: 'm_1', label: "1月份" },
                    { name: 'm_2', label: "2月份" },
                    { name: 'm_3', label: "3月份" },
                    { name: 'm_4', label: "4月份" },
                    { name: 'm_5', label: "5月份" },
                    { name: 'm_6', label: "6月份" },
                    { name: 'm_7', label: "7月份" },
                    { name: 'm_8', label: "8月份" },
                    { name: 'm_9', label: "9月份" },
                    { name: 'm_10', label: "10月份" },
                    { name: 'm_11', label: "11月份" },
                    { name: 'm_12', label: "12月份" }
                ],
            });


            table.on('click', 'tbody td:not(:first-child :last-child)', function (e) {
                if ($(this).parents('#tyTable').length ) {
                    editor.inline(this, {
                        onBlur: 'submit',
                    });
                }

            });
            // Edit record
            table.on('click', 'a.editor_edit', function (e) {
                e.preventDefault();
                editor.edit( $(this).closest('tr'), {
                    title: '编辑年度产值',
                    buttons: '更新'
                } );
            } );

            // Delete a record
            table.on('click', 'a.editor_remove', function (e) {
                e.preventDefault();
                editor.remove( $(this).closest('tr'), {
                    title: '删除该年度的项目产值',
                    message: '您确定要删除该年度的项目产值吗？',
                    buttons: '删除'
                });
            } );
        });
    </script>
@endpush