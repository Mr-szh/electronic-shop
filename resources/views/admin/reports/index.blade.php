<link rel="stylesheet" href="{{ URL::asset('/css/reports.css') }}" />

<div class="row">
    <div class="col-md-4 col-xl-4">
        <div class="card mb-3 widget-content bg-midnight-bloom" style="width: 460px;height: 90px;margin-bottom: 30px !important;background-image: linear-gradient(-20deg, #2b5876 0%, #4e4376 100%) !important;
        position: relative;display: flex;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(26, 54, 126, 0.125);border-radius: .25rem;">
            <div class="widget-content-wrapper" style="width:430px;height:43px;display: flex;flex: 1;position: relative;align-items: center;color: #fff !important;">
                <div class="widget-content-left" style="box-sizing: border-box;padding-left:30px;">
                    <div class="widget-heading" style="opacity: .8;font-weight: bold;font-size:2rem;">待处理订单数</div>
                    <div class="widget-subheading" style="opacity: .5;font-size:1.5rem;">Order pending</div>
                </div>
                <div class="widget-content-right" style="margin-left: auto;box-sizing: border-box;">
                    <div class="widget-numbers" style="color: #fff !important;">
                        <span style="font-weight: bold;font-size: 3.5rem;">{{ $order_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-4">
        <div class="card mb-3 widget-content" style="width:460px;height:90px;margin-bottom: 30px !important;background-image: radial-gradient(circle 248px at center, #16d9e3 0%, #30c7ec 47%, #46aef7 100%) !important;
        position: relative;display: flex;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(26,54,126,0.125);border-radius: .25rem;">
            <div class="widget-content-wrapper" style="width:430px;height:43px;display: flex;flex: 1;position: relative;align-items: center;color: #fff !important;">
                <div class="widget-content-left" style="box-sizing: border-box;padding-left:30px;">
                    <div class="widget-heading" style="opacity: .8;font-weight: bold;font-size:2rem;">今日注册用户数</div>
                    <div class="widget-subheading" style="opacity: .5;font-size:1.5rem;">Number of registered users today</div>
                </div>
                <div class="widget-content-right" style="margin-left: auto;box-sizing: border-box;">
                    <div class="widget-numbers" style="color: #fff !important;">
                        <span style="font-weight: bold;font-size: 3.5rem;">{{ $users }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-4">
        <div class="card mb-3 widget-content" style="width:460px;height:90px;margin-bottom: 30px !important;background-image: linear-gradient(to top, #0ba360 0%, #3cba92 100%) !important;
        position: relative;display: flex;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(26,54,126,0.125);border-radius: .25rem;">
            <div class="widget-content-wrapper" style="width:430px;height:43px;display: flex;flex: 1;position: relative;align-items: center;color: #fff !important;">
                <div class="widget-content-left" style="box-sizing: border-box;padding-left:30px;">
                    <div class="widget-heading" style="opacity: .8;font-weight: bold;font-size:2rem;">今日销量</div>
                    <div class="widget-subheading" style="opacity: .5;font-size:1.5rem;">Today's sales</div>
                </div>
                <div class="widget-content-right" style="margin-left: auto;box-sizing: border-box;">
                    <div class="widget-numbers" style="color: #fff !important;">
                        <span style="font-weight: bold;font-size: 3.5rem;">{{ $sales }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="main" style="width: 800px;height:400px;"></div> -->
<div class="box box-info">
    <div id="item" style="width: 1450px;height:400px;"></div>
</div>

<div class="box box-info" style="width:1400px;">
    <div id="users" style="width: 700px;height:400px;float:left;"></div>
    <div id="sount" style="width: 700px;height:400px;float:right;"></div>
</div>
<!-- <div class="box box-info">
    
    <div id="accessories" style="width: 600px;height:400px;float:right;"></div>
</div> -->

<script type="text/javascript" src="{{ asset('js/echarts.min.js') }}"></script>
<!-- <script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));

    // 指定图表的配置项和数据
    var option = {
        title: {
            text: 'ECharts 入门示例'
        },
        tooltip: {},
        legend: {
            data: ['销量']
        },
        xAxis: {
            data: ["衬衫", "羊毛衫", "雪纺衫", "裤子", "高跟鞋", "袜子"]
        },
        yAxis: {},
        series: [{
            name: '销量',
            type: 'bar',
            data: [5, 20, 36, 10, 10, 20]
        }]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script> -->

<!-- <script>
    // 绘制图表。
    echarts.init(document.getElementById('main')).setOption({
        series: {
            type: 'pie',
            data: [{
                    name: 'A',
                    value: 1212
                },
                {
                    name: 'B',
                    value: 2323
                },
                {
                    name: 'C',
                    value: 1919
                }
            ]
        }
    });
</script> -->

<!-- <script>
    echarts.init(document.getElementById('main')).setOption({
        title: {
            text: 'Line Chart'
        },
        tooltip: {},
        toolbox: {
            feature: {
                dataView: {},
                saveAsImage: {
                    pixelRatio: 2
                },
                restore: {}
            }
        },
        xAxis: {},
        yAxis: {},
        series: [{
            type: 'line',
            smooth: true,
            data: [
                [12, 5],
                [24, 20],
                [36, 36],
                [48, 10],
                [60, 10],
                [72, 20]
            ]
        }]
    });
</script> -->

<!-- <script>
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    // 绘制图表
    myChart.setOption({
        title: {
            text: 'ECharts 入门示例'
        },
        tooltip: {},
        xAxis: {
            data: ['衬衫', '羊毛衫', '雪纺衫', '裤子', '高跟鞋', '袜子']
        },
        yAxis: {},
        series: [{
            name: '销量',
            type: 'bar',
            data: [5, 20, 36, 10, 10, 20]
        }]
    });
</script> -->

<!-- <script>
    var myChart = echarts.init(document.getElementById('main'), 'light');

    myChart.setOption({
        visualMap: {
            show: false,
            min: 80,
            max: 600,
            inRange: {
                colorLightness: [0, 1]
            }
        },
        series: [{
            name: '访问来源',
            type: 'pie',
            radius: '55%',
            data: [{
                    value: 235,
                    name: '视频广告'
                },
                {
                    value: 274,
                    name: '联盟广告'
                },
                {
                    value: 310,
                    name: '邮件营销'
                },
                {
                    value: 335,
                    name: '直接访问'
                },
                {
                    value: 400,
                    name: '搜索引擎'
                }
            ],
            roseType: 'angle',
            labelLine: {
                normal: {
                    lineStyle: {
                        color: 'rgba(255, 255, 255, 0.3)'
                    }
                }
            },
            itemStyle: {
                normal: {
                    shadowBlur: 200,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }]
    })
</script> -->
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('item'), 'light');

    // 指定图表的配置项和数据
    var option = {
        title: {
            text: '单品销量情况'
        },
        tooltip: {},
        legend: {
            data: ['单品销量']
        },
        xAxis: {
            data: {!!$category_key!!}
        },
        yAxis: {},
        series: [{
            name: '销量',
            type: 'bar',
            data: {!!$category_value!!}
        }]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('sount'));
    var arr = []; //用来存放最近七天的时间

    function getBeforeDate(n) {
        var n = n;
        var d = new Date();
        var year = d.getFullYear();
        var mon = d.getMonth() + 1;
        var day = d.getDate();

        if (day <= n) {
            if (mon > 1) {
                mon = mon - 1;
            } else {
                year = year - 1;
                mon = 12;
            }
        }

        d.setDate(d.getDate() + n);
        year = d.getFullYear();
        mon = d.getMonth() + 1;
        day = d.getDate();
        s = (mon < 10 ? ('0' + mon) : mon) + "-" + (day < 10 ? ('0' + day) : day);

        return s;
    }

    for (var i = 0; i > -7; i--) {
        arr.push(getBeforeDate(i));
    }

    option = {
        title: {
            text: '本周销量走势'
        },
        tooltip: {
            trigger: 'axis',
        },
        xAxis: {
            type: 'category',
            name: '日期',
            boundaryGap: false,
            data: arr.reverse(),
            nameGap: 7,
            axisTick: {
                inside: true
            }
        },
        yAxis: {
            type: 'value',
            name: '',
            splitLine: {
                show: false
            },
            nameGap: 15,
            axisTick: {
                inside: true
            }
        },
        series: [{
            type: 'line',
            name: '订单总数',
            data: {!!$seven_sales!!},
            symbol: 'circle',
            itemStyle: {
                normal: {
                    shadowBlur: 50,
                    shadowColor: 'red',
                    color: 'red',
                    lineStyle: {
                        color: '#4d6dfd',
                        width: 1
                    }
                }
            }
        }],
    };

    myChart.setOption(option);
</script>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('users'));
    var arr = []; //用来存放最近七天的时间

    function getBeforeDate(n) {
        var n = n;
        var d = new Date();
        var year = d.getFullYear();
        var mon = d.getMonth() + 1;
        var day = d.getDate();

        if (day <= n) {
            if (mon > 1) {
                mon = mon - 1;
            } else {
                year = year - 1;
                mon = 12;
            }
        }

        d.setDate(d.getDate() + n);
        year = d.getFullYear();
        mon = d.getMonth() + 1;
        day = d.getDate();
        s = (mon < 10 ? ('0' + mon) : mon) + "-" + (day < 10 ? ('0' + day) : day);

        return s;
    }

    for (var i = 0; i > -7; i--) {
        arr.push(getBeforeDate(i));
    }

    option = {
        title: {
            text: '本周新用户注册走势'
        },
        tooltip: {
            trigger: 'axis',
        },
        xAxis: {
            type: 'category',
            name: '日期',
            boundaryGap: false,
            data: arr.reverse(),
            nameGap: 7,
            axisTick: {
                inside: true
            }
        },
        yAxis: {
            type: 'value',
            name: '',
            splitLine: {
                show: false
            },
            nameGap: 15,
            axisTick: {
                inside: true
            }
        },
        series: [{
            type: 'line',
            name: '用户总数',
            data: {!!$seven_users!!},
            symbol: 'circle',
            itemStyle: {
                normal: {
                    shadowBlur: 50,
                    shadowColor: 'red',
                    color: 'red',
                    lineStyle: {
                        color: '#4d6dfd',
                        width: 1
                    }
                }
            }
        }],
    };

    myChart.setOption(option);
</script>