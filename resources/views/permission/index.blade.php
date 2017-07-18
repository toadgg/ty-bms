@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>系统管理</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header no-padding">
                <div class="mailbox-read-info">
                    <h3>权限管理</h3>
                </div>
            </div>

            <div class="box-body nprogress">
                <div id="tableShadow" class="table-warp">
                    @include('components.loading')
                    <div style="display:none;" >
                        <table class="table table-bordered table-striped dataTable nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Updated At</th>
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
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/Editor/css/editor.bootstrap.min.css') }}">
@endpush

@push('js')

    <script src="{{ asset('/adminlte/plugins/select2/select2.full.min.js') }}"></script>

    <!-- InputMask -->
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.numeric.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/jquery.inputmask.min.js') }}"></script>

    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Editor/js/dataTables.editor.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Editor/js/editor.bootstrap.min.js') }}"></script>

    <script>
        var editor;
        $(document).ready(function(){
            var columns = [
                { data: 'id', visible: true, searchable: false },
                { data: 'name' }, { data: 'display_name' }, { data: 'description' }, { data: 'created_at' }, { data: 'updated_at' },
            ];

            var fields = [
                {
                    name: 'name',
                    label: 'name',
                },
                {
                    name: 'display_name',
                    label: "display name"
                },
                {
                    name: 'description',
                    label: "description"
                }
            ];

            editor = new $.fn.dataTable.Editor({
                ajax: {
                    url : "{{ route('permissions.store') }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                },
                table: 'table',
                idSrc:  'id',
                fields: fields
            });

            $.fn.dataTable.ext.errMode = function(s,h,m){};
            var table = $('table').DataTable({
                dom: "Bfrtip",
                processing: true,
                //serverSide: true,
                ajax: '{!! route('permissions.index') !!}',
                columns: columns.concat({
                    data: null,
                    orderable: false,
                    className: "center",
                    defaultContent: '<div class="">' +
                                        '<a href="" class="editor_edit btn btn-xs btn-default"><span class="fa fa-fw fa-pencil"></span></a> ' +
                                        '<a href="" class="editor_remove btn btn-xs btn-default"><span class="fa fa-fw fa-trash"></span></a>' +
                                    '</div>'
                }),
                select: true,
                buttons: [{
                        text: '',
                        className: 'fa fa-plus',
                        action: function (e, dt, node, config) {
                            editor.create( {
                                title: '创建新权限',
                                buttons: '创建'
                            })
                        }
                }],
            });


            // Edit record
            table.on('click', 'a.editor_edit', function (e) {
                e.preventDefault();

                editor.edit( $(this).closest('tr'), {
                    title: '编辑权限',
                    buttons: '更新'
                } );
            } );

            // Delete a record
            table.on('click', 'a.editor_remove', function (e) {
                e.preventDefault();

                editor.remove( $(this).closest('tr'), {
                    title: '删除权限',
                    message: '您确定要删除此权限吗？',
                    buttons: '删除'
                });
            } );
        });
    </script>
@endpush