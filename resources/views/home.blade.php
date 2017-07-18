@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-building-o "></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">项目</span>
                    <span class="info-box-number">{{ $projects->where('status', '在建')->count() }}<small>在建/{{ $projects->count() }}</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>

    <div class="row">

        <div class="col-md-12" >
            <div id="main" class="info-box" style="height:800px;">
            <div>
        </div>
    </div>
@stop

@push('js')
    <script type="text/javascript">

        Pusher.log = function(msg) {
            console.log(msg);
        };
        Echo.channel('test')
            .listen('TestPusherEvent', (e) => {
                console.log(e.msg);
            });

        axios.get('data/map/china.json')
            .then(function (response) {
            echarts.registerMap('china', response.data);
            //var chart = echarts.init(document.getElementById('main'));

            var data = {!!  json_encode(array_column_multi($projects->toArray(), ['name', 'contract_money']))  !!};
            var geoCoordMap = {!! json_encode($values) !!};

            var convertData = function (data) {
                var res = [];
                for (var i = 0; i < data.length; i++) {
                    var geoCoord = geoCoordMap[data[i].name];
                    if (geoCoord) {
                        res.push({
                            name: data[i].name,
                            value: geoCoord.concat(data[i].contract_money/10000000)
                        });
                    }
                }
                return res;
            };

            option = {
                backgroundColor: '#404a59',
                title: {
                    text: '天一建设集团项目分布图',
                    subtext: 'data from tianyijianshe',
                    sublink: 'http://www.tianyijianshe.com/',
                    left: 'center',
                    textStyle: {
                        color: '#fff'
                    }
                },
                tooltip : {
                    trigger: 'item'
                },
                legend: {
                    orient: 'vertical',
                    y: 'bottom',
                    x:'right',
                    data:['项目金额(千万)'],
                    textStyle: {
                        color: '#fff'
                    }
                },
                geo: {
                    map: 'china',
                    label: {
                        emphasis: {
                            show: false
                        }
                    },
                    roam: false,
                    itemStyle: {
                        normal: {
                            areaColor: '#323c48',
                            borderColor: '#111'
                        },
                        emphasis: {
                            areaColor: '#2a333d'
                        }
                    }
                },
                series : [
                    {
                        name: '项目金额(千万)',
                        type: 'scatter',
                        coordinateSystem: 'geo',
                        data: convertData(data),
                        symbolSize: function (val) {
                            return val[2] / 10;
                        },
                        label: {
                            normal: {
                                formatter: '{b}',
                                position: 'right',
                                show: false
                            },
                            emphasis: {
                                show: true
                            }
                        },
                        itemStyle: {
                            normal: {
                                color: '#ddb926'
                            }
                        }
                    },
                    {
                        name: 'Top 10',
                        type: 'effectScatter',
                        coordinateSystem: 'geo',
                        data: convertData(data.sort(function (a, b) {
                            return b.contract_money - a.contract_money;
                        }).slice(0, 11)),
                        symbolSize: function (val) {
                            return val[2] / 10;
                        },
                        showEffectOn: 'render',
                        rippleEffect: {
                            brushType: 'stroke'
                        },
                        hoverAnimation: true,
                        label: {
                            normal: {
                                formatter: '{b}',
                                position: 'right',
                                show: true
                            }
                        },
                        itemStyle: {
                            normal: {
                                color: '#f4e925',
                                shadowBlur: 10,
                                shadowColor: '#333'
                            }
                        },
                        zlevel: 1
                    }
                ]
            };
            //chart.setOption(option);
        });
    </script>
@endpush