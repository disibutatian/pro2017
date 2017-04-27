<?php

class Outputor
{
    protected $m_env = array();
    protected $m_buf = array();

    public function __construct($conf)
    {
        $this->m_env['max_line'] = intval($conf['max_line']);
        $this->m_env['max_size'] = intval($conf['max_size']);
        $this->m_env['max_interval'] = intval($conf['max_interval']);
        $this->m_env['immediate_send'] = $conf['immediate_send'];
            
        $this->current_size = 0;
        $this->current_line = 0;
        $this->last_time = time();
    }

    public function  insertQueue($id, $param)
    {
            
    }

    public static function createOutputor($type, $conf)
    {
        $class_name = $type . "Outputor";
        if(class_exists($class_name))
            return new $class_name($conf);

        return new Outputor($conf);
    }

}

class CloudMarkHttpPostOutputor extends OutPutor
{
    protected $m_old_buf = array();  //cache log data.

    public function __construct($conf)
    {
        $this->m_env['max_line'] = intval($conf['max_line']);
        $this->m_env['max_size'] = intval($conf['max_size']);
        $this->m_env['max_interval'] = intval($conf['max_interval']);
        $this->m_env['immediate_send'] = $conf['immediate_send'];
        $this->m_env['check_post_res'] = $conf['check_post_res'];
    
        $this->m_env['http_timeout'] = intval($conf['http_timeout']);
        $this->m_env['http_retry'] = intval($conf['http_retry']);
        $this->m_env['domain']  = $conf['domain'];
        $this->m_env['uri'] = $conf['uri'];
        $this->m_env['host'] = $conf['host'];

        $this->current_size = 0;
        $this->current_line = 0;
        $this->last_time = time();

    }
    
    //param is the array of string log  after filtering...

    public function insertQueue($id, $param)
    {
        $this->m_old_buf = $this->m_buf; 

        if(!empty($param))
        {
            foreach($param as $post_str)
            {   
                array_push($this->m_buf, $post_str);
                $this->current_line++;
            }
        } 
        $current_time = time();
        if($this->m_env['immediate_send'])
            return $this->sendAll();
        elseif($this->current_line > $this->m_env['max_line'])
            return $this->sendAll();
        elseif($current_time - $this->last_time > $this->m_env['max_interval'])
            return $this->sendAll();
    
        return true;

    }    

    public function sendAll()
    {
        if(!empty($this->m_buf))
        {
            $post_str = implode("\n", $this->m_buf);
            $url = "http://" . $this->m_env['domain'] . $this->m_env['uri'];
            $header = array(
                'Host: ' . $this->m_env['host'],
                );
            $options = array(
                'header' => $header,
                'timeout' => $this->m_env['http_timeout'],
                'retry' => $this->m_env['http_retry'],
            );

            $data = array(
                'id'  => 7,
                'data' => $post_str,
                'user' => '360mobile',
                'passwd' => '25fcaa24e55485c41b4fbe5114c85c21',
            );

            $res = $this->http($url, $data, $options);
        }

        if( $this->m_env['check_post_res'] &&  !empty($res) && $res['retCode'] != 200 )
        {
            $this->popQueue();
            $this->current_line = is_array($this->m_buf) ? count($this->m_buf) : 0;
            return false;
        }
        else
        {
            $this->last_time = time();
            $this->current_line = 0; //is_array($this->m_buf) ? count($this->m_buf) : 0;
            $this->m_buf = array();
            return true;
        }
 
    }

    public static function http($url, $postfield = null, $options = null)
    {
        $ch = $curl_init();  
            
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!empty($postfield)) //post request
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
        }
        
        if (!empty($options['header']) && is_array($options['header']))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $options['header']);
        }

        if (!empty($options['timeout']) && is_numeric($options['timeout']))
        {
            curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
        }
        
        $retry = intval($options['retry']);
        if(empty($retry))
            $retry = 1;
        $retry_count = 0;
        while($retry_count < $retry)
        {
            $resp = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($code == 200)
                break;
            $retry_count++;

        }
        return array('retCode'=>$code, 'retMsg'=>$resp);
        
    }
 
}








