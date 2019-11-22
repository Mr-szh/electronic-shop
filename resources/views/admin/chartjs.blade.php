<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">数据统计</h3>
        <div class="box-tools">
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td width="20%">今日注册用户数：</td>
                    <td style="color: red">{{ $users }}</td>
                </tr>
                <tr>
                    <td width="20%">今日销量：</td>
                    <td style="color: blueviolet">{{ $sales }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="main" style="width: 600px;height:400px;"></div>

<script type="text/javascript" src="{{ asset('js/echarts.min.js') }}"></script>
<script type="text/javascript">
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
</script>