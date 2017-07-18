@extends('adminlte.page')

@section('content_header')
    <h1>系统管理</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header no-padding">
                    <div class="mailbox-read-info">
                        <h3>基本档案同步</h3>
                    </div>

                    <div class="nprogress mailbox-read-info">
                        <div class="row">
                            <div class="col-md-12 no-padding">
                                <div class="form-group">
                                    <label class="col-md-12">数据来源</label>
                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">DRIVER</span>
                                            <input type="text" value="{{ $ncdb['driver'] }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">DATABASE</span>
                                            <input type="text" value="{{ $ncdb['database'] }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">HOST</span>
                                            <input type="text" value="{{ $ncdb['host'] }}:{{ $ncdb['port'] }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">USERNAME</span>
                                            <input type="text" value="{{ $ncdb['username'] }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <form action="{{ route('settings.sync.attachments') }}" method="POST">
                                    {{ csrf_field() }}
                                    <button id="syncAttachmentsBtn" type="submit" class="btn btn-block btn-primary btn-flat">合同附件同步</button>
                                </form>
                            </div>

                            <div class="col-md-2 pull-right">
                                <form action="{{ route('settings.sync.all') }}" method="POST">
                                    {{ csrf_field() }}
                                    <button id="syncBtn" type="submit" class="btn btn-block btn-primary btn-flat">基本档案同步</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div id="tableShadow" class="table-warp">
                        @include('components.loading')
                        <div style="display:none;" >
                            <table class="table table-bordered table-striped dataTable nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>基本档案名称</th>
                                    <th>本地数据</th>
                                    <th>服务器数据</th>
                                    <th>上次同步时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>项目基本档案</td>
                                    <td>{{ $summary['pcount'] }}</td>
                                    <td>{{ $summary['rpcount'] }}</td>
                                    <td>{{ $summary['psync'] }}</td>
                                </tr>
                                <tr>
                                    <td>合同基本档案</td>
                                    <td>{{ $summary['ccount'] }}</td>
                                    <td>{{ $summary['rccount'] }}</td>
                                    <td>{{ $summary['csync'] }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function(){
        NProgress.done();
    });
</script>
@endpush