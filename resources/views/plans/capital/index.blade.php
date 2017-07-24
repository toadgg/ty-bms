@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>工作台</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">资金计划管理</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body nprogress">
                <div id="tableShadow" class="table-warp">
                    @include('components.loading')
                    <div style="display:none;" >
                        <table id="table" class="table table-bordered table-striped dataTable nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>项目名称</th>
                                <th>板块</th>
                                <th>计划日期</th>
                                <th>计划支付金额</th>
                                @foreach($categories as $category)
                                    <th>{{ $category->name }}</th>
                                @endforeach
                                <th>统计时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($plans as $index=>$plan)
                                <tr>
                                    <td>{{ $plan->id }}</td>
                                    <td>{{ $plan->project->name }}</td>
                                    <td>{{ $plan->plate->name }}</td>
                                    <td>{{ $plan->calendar }}</td>
                                    <td class="text-right">
                                        {{ format_currency($plan->details()->sum('payable_in_plan')) }}
                                        <a href="{{ route('plans.capital.show', $plan->id) }}" class="fa fa-search"></a>
                                    </td>
                                    @foreach($categories as $category)
                                        <td class="text-right">
                                            {{ format_currency($plan->details()->where('category_id', $category->id)->sum('payable_in_plan')) }}
                                            <a href="{{ route('plans.capital.details.index', [$plan->id, $category->id]) }}" class="fa fa-plus"></a>
                                        </td>
                                    @endforeach
                                    <td>{{ $plan->statistical_data ? $plan->statistical_data : '实时统计' }}</td>
                                    <td>
                                        <a href="" class="editor_remove btn btn-xs btn-default"><span class="fa fa-fw fa-trash"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>


<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('plans.capital.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">创建资金计划日历</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>所属板块</label>
                                <select name="plate_id" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    @foreach($plates as $index=>$plate)
                                        <option value="{{ $plate->id }}" @if($index == 0) selected="selected" @endif>{{ $plate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>项目名称</label>
                                <select name="project_id" class="form-control select2" style="width: 100%;">
                                    @foreach($projects as $index=>$project)
                                        <option value="{{ $project->id }}" @if(!$project->contract) disabled @endif>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>计划日期</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="calendar" value="@php echo date("mY") @endphp" type="text" class="form-control calendar">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">创建</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/RowGroup/css/rowGroup.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/datatables/extensions/Editor/css/editor.bootstrap.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('/adminlte/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/bootbox/bootbox.min.js') }}"></script>


    <script src="{{ asset('/adminlte/plugins/datatables/extensions/RowGroup/js/dataTables.rowGroup.js') }}"></script>

    <!-- InputMask -->
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.date.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/jquery.inputmask.min.js') }}"></script>

    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Editor/js/dataTables.editor.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/datatables/extensions/Editor/js/editor.bootstrap.min.js') }}"></script>

    <script type="text/javascript">
        function formatState (state) {
            if (!state.id) { return state.text; }
            if(state.disabled){
                var $state = $(
                    '<span><span class="fa fa-warning" /></span> ' + state.text + '</span>'
                );
                return $state;
            } else {
                return state.text;
            }
        };

        $("select").select2({
            templateResult: formatState
        });

        $(".calendar").inputmask('mm/yyyy', {
            removeMaskOnSubmit: true,
            yearrange: {
                minyear: 2010, maxyear: 2020
            }
        });

        $(document).ready(function(){
            $.fn.dataTable.defaults.buttons.unshift({
                text: '',
                className: 'fa fa-calendar-plus-o',
                action: function ( e, dt, node, config) {
                    $('#createModal').modal('show');
                }
            });

            var table = $('table').DataTable({
                order: [[3, 'desc']],
                rowGroup: {
                    dataSrc: 3
                }
            });

            table.on('click', 'a.editor_remove', function (e) {
                e.preventDefault();
                var url = '{{ route('plans.capital.store') }}';
                var id = table.row($(this).closest('tr')).data()[0];
                var thisRow = $(this).closest('tr');
                bootbox.confirm({
                    title: "删除资金计划日历",
                    message: "此操作会自动删除关联的计划明细，您确定删除该计划吗？",
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> 取消'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> 确定'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            axios.delete( url + '/' + id).then(function (response) {
                                table.row(thisRow).remove().draw(false);
                            }).catch(function (error) {
                                console.log(error);
                            });
                        }
                    }
                });
            });
        });

    </script>
@endpush