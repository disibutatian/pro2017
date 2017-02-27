<?php
    
include_once 'conf.php';

if( !isset($g_conf[$g_work_name]))
    exit("$g_work_name error");

if($g_conf[$g_work_name]['log_parser'] === 'PhoneCall')
    echo "log_parser is true\n";

$line_list = $g_conf[$g_work_name]['line_list'];
if($line_list['cloud_mark']['filter_list']['mark_filter']['type'] === 'CloudMark')
    echo "CloudMark is true\n";

if($line_list['cloud_mark']['outputor_list']['http_post']['type'] === 'CloudMarkHttpPost')
    echo "CloudMarkHttpPost is true\n";

if($line_list['tucao']['filter_list']['tucao_filter']['type'] === 'Tucao')
    echo "Tucao is true\n";

if($line_list['tucao']['outputor_list']['http_post']['type'] === 'HttpPost')
    echo "HttpPost is true\n";







