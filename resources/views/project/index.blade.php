@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>档案管理</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">项目档案管理</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body nprogress">
                <div id="tableShadow" class="table-warp">
                    @include('components.loading')
                    <div style="display:none;" >
                        <table class="table table-bordered table-striped dataTable nowrap" cellspacing="0" width="100%" >
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>可见</th>
                                <th>工程名称</th>
                                <th>项目状态</th>
                                <th>结算方式</th>
                                <th>建设单位</th>
                                <th>施工单位</th>
                                <th data-class="text-right">合同金额</th>
                                <th data-class="text-right">建筑面积</th>
                                <th>开工日期</th>
                                <th>竣工日期</th>
                                <th data-class="text-right">预付款</th>
                                <th data-class="text-right">进度款</th>
                                <th>中标项目经理</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($projects as $index=>$project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td><input class="editor_visible" type="checkbox" @if($project->visible == 'on') checked >@endif</td>
                                    <td>{{ $project->name }}@if($project->contract) <a href="{{ route('contracts.show', $project->contract['id']) }}" target="_blank"><i class="fa fa-fw fa-file"></i></a> @endif</td>
                                    <td>{{ $project->status }}</td>
                                    <td>{{ $project->contract['pay_mode'] === 0 ? '按进度' : '按部位' }}</td>
                                    <td>{{ $project->developer }}</td>
                                    <td>{{ $project->contractor }}</td>
                                    <td class="currency">{{ format_currency($project->contract['signed_money']) }}</td>
                                    <td class="decimal">{{ format_decimal($project->build_area, '㎡') }}</td>
                                    <td>{{ $project->start }}</td>
                                    <td>{{ $project->finish }}</td>
                                    <td class="currency">{{ format_currency($project->contract['advance_payment_amount']) }}</td>
                                    <td class="decimal">{{ format_decimal($project->contract['progress_payment_pct'], '%') }}</td>
                                    <td>{{ $project->manager }}</td>
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
@stop

@push('css')
<link rel="stylesheet" href="{{ asset('/adminlte/plugins/iCheck/all.css') }}">

@endpush

@push('js')

<script src="{{ asset('/adminlte/plugins/iCheck/icheck.min.js') }}"></script>

<script type="text/javascript">
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%'
    });

    var table = $('table').DataTable({
        order: [[ 9, "desc" ]],
        columnDefs: [{
            targets: 0,
            visible: false,
            searchable: false
        },{
            targets: 1,
            orderable: false,
            searchable: false
        }],
        initComplete: function () {
            NProgress.done();
            $('.dt-buttons').css({ 'z-index': 2 });
            $('<div id="filter-container" class="col-md-9"></div>').prependTo($('.dataTables_filter'));
            this.api().columns([3, 4]).every( function () {
                var column = this;
                $('<label>' + $(column.header()).text().trim() + '</label>').appendTo($('#filter-container'));
                var select = $('<select class="form-control input-sm"><option value="">全部</option></select>')
                    .appendTo($('#filter-container'))
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    select.append('<option value="' + d + '">' +  d +  '</option>')
                });
            });
        }
    });

    $('body').on('ifToggled', 'input', function(event){
        var checked = event.target.checked;
        var base_url = '{{ route('projects.index') }}';
        var id = table.row($(this).closest('tr')).data()[0];
        var thatCheckBox = $(this);
        axios.put(base_url + '/' + id, {
            visible: checked ? 'on' : 'off'
        }).then(function (response) {
            thatCheckBox.iCheck(response.data.visible == 'on' ? 'check' : 'uncheck');
        }).catch(function (error) {
            console.log(error);
        });
    });


</script>
@endpush