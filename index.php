<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'device.php');
?>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Suifu の Pi Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <script src="assets/jquery-3.1.1.min.js"></script>
    <script src="assets/highcharts.js"></script>
    <script src="assets/highcharts-more.js"></script>
    <script src="assets/solid-gauge.js"></script>
    <script src="assets/exporting.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script language="JavaScript">
        window.dashboard_old = null;
        window.dashboard = null;
        var init_vals = eval('('+"{'mem': {'total':<?php echo($D['mem']['total']) ?>,'swap':{'total':<?php echo($D['mem']['swap']['total']) ?>}}, 'disk': {'total':<?php echo($D['disk']['total']) ?>}, 'net': { 'count': <?php echo($D['net']['count']) ?>} }"+')');
    </script>
    <style type="text/css">
        .label {color: #ddd; font-size: 75%; font-weight: bolder;}
    </style>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img class="logo-ani-cls" style="height: 100%; display: inline;" src="assets/logo.png">Suifu の Pi 仪表盘</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">关于 <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a target="_blank" href="https://omg.byeguo.cn">我的博客</a></li>
                            <li><a target="_blank" href="https://github.com/betterTisen">我的github</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="padding-top: 50px;">
        <div class="row">
            <div class="col-md-3">
                <div style="text-align: center; padding: 20px 0;"><img class="logo-img" src="assets/devices/raspberry-pi.png" /></div>
                <div style="background-color: #f6f6f6; padding: 5px; border-radius: 3px;">
                    <div class="text-center ani-cls" style="margin:10px; padding: 10px 0 10px 0; border-radius: 3px;"><div id="pimodel" style="color:#688d89;font-size: 90%; font-weight: bolder; text-shadow: 0 1px 0 #fff;"><?php echo($D['model']['pimodel']); ?></div></div>
                    <div class="text-center ani-cls" style="margin:20px; padding: 10px 0 10px 0; background-color:#688d89; border-radius: 3px;"><div class="label">IP</div><div id="hostip" style="font-size: 150%; font-weight: bolder;"><?php echo($D['hostip']); ?></div></div>
                    <div class="text-center ani-cls" style="margin:20px; padding: 10px 0 10px 0; background-color:#688d89; border-radius: 3px;"><div class="label">TIME</div><div id="time" style="font-size: 150%; font-weight: bolder;">00:00</div><div id="date">-</div></div>
                    <div class="text-center ani-cls" style="margin:20px; padding: 10px 0 10px 0; background-color:#688d89; border-radius: 3px;"><div class="label">UPTIME</div><div id="uptime" style="font-size: 120%; font-weight: bolder;">0</div></div>
                    <div class="text-center ani-cls" style="margin:20px; padding: 10px 0 10px 0; background-color:#94b9b6; border-radius: 3px;"><div class="label">USER</div><div id="user" style="font-size: 120%; font-weight: bolder;"><?php echo($D['user']); ?></div></div>
                    <div class="text-center ani-cls" style="margin:20px; padding: 10px 0 10px 0; background-color:#94b9b6; border-radius: 3px;"><div class="label">OS</div><div id="os" style="font-size: 120%; font-weight: bolder;"><?php echo($D['os'][0]); ?></div></div>
                    <div class="text-center ani-cls" style="margin:20px; padding: 10px 0 10px 0; background-color:#94b9b6; border-radius: 3px;"><div class="label">HOSTNAME</div><div id="hostname" style="font-size: 110%; font-weight: bolder;"><?php echo($D['hostname']); ?></div></div>
                    <div class="text-center ani-cls" style="margin:20px; padding: 10px 0 10px 0; background-color:#b7d4d1; border-radius: 3px;"><div id="uname" style="word-break:break-all; word-wrap:break-word; font-size: 12px; color: #fff; padding: 0 10px;"><?php echo($D['uname']); ?></div></div>
                </div>
            </div>
            <div class="col-md-9 ani-cls">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div id="container-cpu" style="width: 100%; height: 200px;"></div>
                        <div style="height: 200px;">
                            <div class="row" style="margin: 0; background-color:#94b9b6;">
                                <div class="text-center" style="padding: 2px 0 2px 0; background-color: #5e5047;"><strong><small>CPU</small></strong></div>
                                <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#6d6058;"><span id="cpu-freq" style="font-weight: bolder;"><?php echo($D['cpu']['freq']/1000) ?></span><br /><small class="label">MHz</small></div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#6d6058;"><span id="cpu-count"><?php echo($D['cpu']['count']) ?></span><br /><small class="label">CORE</small></div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#6d6058;"><span id="cpu-temp" style="font-weight: bolder;">0</span><br /><small class="label">C°</small></div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#6d6058;"><span id="cpu-stat-idl">0</span>%<br /><small class="label">IDLE</small></div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#7ba5a1;"><span id="cpu-stat-use">0</span>%<br /><small class="label">USER</small></div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#7ba5a1;"><span id="cpu-stat-sys">0</span>%<br /><small class="label">SYS</small></div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#7ba5a1;"><span id="cpu-stat-nic">0</span>%<br /><small class="label">NICE</small></div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#7ba5a1;"><span id="cpu-stat-iow">0</span>%<br /><small class="label">IOW</small></div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#7ba5a1;"><span id="cpu-stat-irq">0</span>%<br /><small class="label">IRQ</small></div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color:#7ba5a1;"><span id="cpu-stat-sirq">0</span>%<br /><small class="label">SIRQ</small></div>
                                </div>
                                <div class="col-md-12" style="min-height: 52px;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; margin: auto 0;"><span id="cpu-model" class="label"><?php echo($D['cpu']['model']) ?></span>&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div id="container-mem" style="width: 100%; height: 200px;"></div>
                        <div style="height: 200px;">
                            <div class="row" style="margin: 0; background-color:#7ba5a1;">
                                <div class="text-center" style="padding: 2px 0 2px 0; background-color: #5e5047;"><strong><small>MEMORY</small></strong></div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color: #6d6058;"><span id="mem-percent">0</span>%<br /><small class="label">USED</small></div>
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color: #6d6058;"><span id="mem-free">0</span>MB<br /><small class="label">FREE</small></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color: #5e5047;"><span id="mem-cached">0</span>MB<br /><small class="label">CACHED</small></div>
                                    <div class="text-center" style="padding: 10px 0 10px 0; background-color: #5e5047;"><span id="mem-swap-total">0</span>MB<br /><small class="label">SWAP</small></div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="loadavg-1m">0.0</span><br /><small class="label">AVG.1M</small></div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="loadavg-5m">0.0</span><br /><small class="label">AVG.5M</small></div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="loadavg-10m">0.0</span><br /><small class="label">AVG.10M</small></div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><strong><span id="loadavg-running">0</span>/<span id="loadavg-threads">0</span></strong><br /><small class="label">RUNNING</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div id="container-cache" style="width: 100%; height: 100px;"></div>
                        <div style="height: 90px;">
                            <div class="row" style="margin: 0; background-color:#7ba5a1;">
                                <div class="text-center" style="padding: 2px 0 2px 0; background-color: #6d6058;"><strong><small>CACHE</small></strong></div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="mem-cache-percent">0</span>%<br /><small class="label">USED</small></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0; background-color:#6d6058;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="mem-buffers">0</span>MB<br /><small class="label">BUFFERS</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div id="container-mem-real" style="width: 100%; height: 100px;"></div>
                        <div style="height: 90px;">
                            <div class="row" style="margin: 0; background-color:#7ba5a1;">
                                <div class="text-center" style="padding: 2px 0 2px 0; background-color: #5e5047;"><strong><small>REAL MEMORY</small></strong></div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="mem-real-percent">0</span>%<br /><small class="label">USED</small></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0; background-color:#5e5047;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="mem-real-free">0</span>MB<br /><small class="label">FREE</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div id="container-swap" style="width: 100%; height: 100px;"></div>
                        <div style="height: 90px;">
                            <div class="row" style="margin: 0; background-color:#7ba5a1;">
                                <div class="text-center" style="padding: 2px 0 2px 0; background-color: #6d6058;"><strong><small>SWAP</small></strong></div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="mem-swap-percent">0</span>%<br /><small class="label">USED</small></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0; background-color:#6d6058;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="mem-swap-free">0</span>MB<br /><small class="label">FREE</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div id="container-disk" style="width: 100%; height: 100px;"></div>
                        <div style="height: 90px;">
                            <div class="row" style="margin: 0; background-color:#7ba5a1;">
                                <div class="text-center" style="padding: 2px 0 2px 0; background-color: #6d6058;"><strong><small>DISK</small></strong></div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="disk-percent">0</span>%<br /><small class="label">USED</small></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0; background-color:#6d6058;">
                                    <div class="text-center" style="padding: 10px 0 10px 0;"><span id="disk-free">0</span>GB<br /><small class="label">FREE</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <?php
                        for($i = 0; $i<$D['net']['count'];$i++)
                        {
                            ?>
                            <div class="row" style="margin: 0;">
                                <div class="col-md-10 col-sm-10 col-xs-10" style="padding: 0;">
                                    <div id="container-net-interface-<?php echo($i+1) ?>" style="min-width: 100%; height: 150px; margin: 20 auto"></div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 0;">
                                    <div style="height: 80px; margin-top: 10px;">
                                        <div class="text-center" style="padding: 2px 0 2px 0; background-color: #CCCCCC;"><strong><span id="net-interface-<?php echo($i+1) ?>-name"><?php echo($D['net']['interfaces'][$i]['name']) ?></span></strong></div>
                                        <div class="text-center" style="padding: 10px 0 10px 0; background-color: #94b9b6;"><span id="net-interface-<?php echo($i+1) ?>-total-in"><?php echo($D['net']['interfaces'][$i]['total_in']) ?></span><br /><small class="label">IN</small></div>
                                        <div class="text-center" style="padding: 10px 0 10px 0; background-color: #5e5047;"><span id="net-interface-<?php echo($i+1) ?>-total-out"><?php echo($D['net']['interfaces'][$i]['total_out']) ?></span><br /><small class="label">OUT</small></div>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="footer">
                    <hr style="margin: 20px 0 10px 0;" />
                    <p class="pull-left" style="font-size: 12px;">Powered by <a target="_blank" href="https://github.com/betterTisen"><strong>Suidu Pi Dashboard</strong></a> v<?php echo($D['version']) ?>, <a target="_blank" href="https://omg.byeguo.cn">byeguo.cn</a> all rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/dashboard.js"></script>
</body>
</html>