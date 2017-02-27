<?php

    $g_work_name = 'text_msg';

    $g_conf = array(
           'text_msg' => array(
               'conf' => array(),
               'log_parser' => 'PhoneCall',
               'log_parser_conf' => array(),
               'line_list' => array(
                   //cloud_mark configuration
                   'cloud_mark' => array(
                       'filter_list' => array(
                           'mark_filter' => array(
                               'type' => 'CloudMark',
                               'conf' => array(
                                   'product_combo_list' => array(),
                                   ),
                               ),
                           ),   //end filter_list
                       'outputor_list' => array(
                           'http_post' => array(
                               'type' => 'CloudMarkHttpPost',
                               'conf' => array(
                                        'max_line' => 500,
                                        'max_size' => 1000000000,
                                        'max_interval' => 30, 
                                        'immediate_send' => false,
                                        'check_post_res' => false,
                                        'http_timeout' => 3,
                                        'http_retry' => 1,
                                        'domain' => '111.206.60.168:8360',
                                        'uri' => '/www/cloud_mark_data.php?src=lycc',
                                        'host' => 'w-swdb1.mobi.bjcc.qihoo.net',
                                ),
                            ),
                        ),  //end outputor_list
                    ),  //end cloud_mark

                    //
                    'tucao' => array(
                    'filter_list' => array(
                        'tucao_filter' => array(
                            'type' => 'TuCao',
                            'conf' => array(
                                ),
                         ),
                      ), // end filter_list
                    'outputor_list' => array(
                        'http_post' => array(
                            'type' => 'HttpPost',
                            'conf' => array(
                                        'max_line' => 200,
                                        'max_size' => 10000000,
                                        'max_interval' => 10,
                                        'immediate_send' => true,
                                        'check_post_res' => false,
                                        'http_timeout' => 10,
                                        'http_retry' => 1,
                                        'domain' => '111.206.60.169:8360',
                                        'uri' => '/tucao/recv.php',
                                        'host' => 'w-swdb2.mobi.bjcc.qihoo.net',
                                ),
                            ),
                        ), // end outputor_list
                    ), // end tucao
        ), // end line_list
     ), // end text_msg
   );
