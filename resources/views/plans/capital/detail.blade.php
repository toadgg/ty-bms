@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>资金计划分项</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header no-padding">
                <div class="mailbox-read-info">
                    <h3>{{ $plan->plate->name }} {{ $plan->calendar }} 资金计划</h3>
                    <h5>项目名称: {{ $plan->project->name }}<span class="mailbox-read-time pull-right">分项类别: {{ $category->name }}</span></h5>
                </div>
            </div>

            <div class="box-body nprogress">
                <div id="tableShadow" class="table-warp">
                    @include('components.loading')
                    <div style="display:none;" >
                        <table class="table table-bordered table-striped dataTable nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>分包单位全称</th>
                                <th>供货内容/分包内容</th>
                                <th data-class="text-right">合同金额</th>
                                <th>付款方式</th>
                                <th data-class="text-right">已完成产值/供货金额</th>
                                <th data-class="text-right">按合同已到期应付金额</th>
                                <th data-class="text-right">按合同未到期应付金额</th>
                                <th data-class="text-right">累计已付金额</th>
                                <th data-class="text-right">本月计划支付金额</th>
                                <th data-class="text-right">实付金额</th>
                                <th>备注</th>
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
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/KeyTable/css/keyTable.bootstrap.min.css') }}">
    <style>
        div.DTE_Inline input {
            border: none;
            background-color: transparent;
            padding: 0 !important;
            font-size: 90%;
        }

        div.DTE_Inline input:focus {
            outline: none;
            background-color: transparent;
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
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/KeyTable/js/dataTables.keyTable.min.js') }}"></script>

    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Editor/js/dataTables.editor.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Editor/js/editor.bootstrap.min.js') }}"></script>

    <script>
        var editor;
        $(document).ready(function(){

            editor = new $.fn.dataTable.Editor({
                ajax: {
                    url : "{{ route('plans.capital.details.store', [$plan->id, $category->id]) }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                },
                table: "#tyTable",
                idSrc:  'id',
                fields: [
                    { name: "capital_plan_id", type:"hidden", def: "{{ $plan->id }}"},
                    { name: "category_id", type:"hidden", def: "{{ $category->id }}"},
                    { name: 'pay_to', label: '分包单位全称' },
                    { name: 'info', label: '供货内容/分包内容' },
                    { name: 'contract_amount', label: '合同金额' },
                    { name: 'pay_mode', label: '付款方式' },
                    { name: 'completed_amount', label: '已完成产值/供货金额' },
                    { name: 'payable_in_contract', label: '按合同已到期应付金额' },
                    { name: 'paid_in_contract', label: '按合同未到期应付金额' },
                    { name: 'paid_in_contract_amount', label: '累计已付金额' },
                    { name: 'payable_in_plan', label: '本月计划支付金额' },
                    { name: 'paid_in_plan', label: '实付金额' },
                    { name: 'remark', label: '备注' },
                ],
            });

            $.fn.dataTable.ext.errMode = 'none';
            $.fn.dataTable.defaults.buttons.unshift(
                {
                    text: '',
                    className: 'fa fa-plus',
                    action: function () {
                        editor.create( {
                            title: '新建资金计划明细',
                            buttons: '创建'
                        })
                    }
                }
            );
            var table = $('#tyTable').DataTable({
                keys: {
                    columns: ':not(:first-child)',
                    editor:  editor
                },
                select: {style: 'os', items: 'cell', info: false},
                ajax: "{{ route('plans.capital.details.index', [$plan->id, $category->id]) }}",
                fixedColumns: {
                    leftColumns: 2,
                    rightColumns: 1
                },
                columns: [
                    { data: 'id', searchable: false },
                    { data: 'pay_to' },
                    { data: 'info' },
                    { data: 'contract_amount', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' ) },
                    { data: 'pay_mode' },
                    { data: 'completed_amount', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' ) },
                    { data: 'payable_in_contract', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' ) },
                    { data: 'paid_in_contract', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' ) },
                    { data: 'paid_in_contract_amount', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' ) },
                    { data: 'payable_in_plan', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' ) },
                    { data: 'paid_in_plan', render: $.fn.dataTable.render.number( ',', '.', 0, '¥ ' ) },
                    { data: 'remark' },
                    { data: null, orderable: false, className: "center",
                        defaultContent: '<div class="">' +
                                            '<a href="" class="editor_edit btn btn-xs btn-default"><span class="fa fa-fw fa-pencil"></span></a> ' +
                                            '<a href="" class="editor_remove btn btn-xs btn-default"><span class="fa fa-fw fa-trash"></span></a>' +
                                        '</div>'
                    },
                ]
            });


            editor
                .on( 'open', function ( e, mode, action ) {
                    if ( mode === 'main' ) {
                        table.keys.disable();
                    }
                })
                .on( 'close', function () {
                    table.keys.enable();
                });

            // Edit record
            table.on('click', 'a.editor_edit', function (e) {
                e.preventDefault();
                editor.edit( $(this).closest('tr'), {
                    title: '编辑资金计划明细',
                    buttons: '更新'
                } );
            } );

            // Delete a record
            table.on('click', 'a.editor_remove', function (e) {
                e.preventDefault();
                editor.remove( $(this).closest('tr'), {
                    title: '删除该资金计划明细',
                    message: '您确定要删除该资金计划明细吗？',
                    buttons: '删除'
                });
            });
        });
    </script>
@endpush