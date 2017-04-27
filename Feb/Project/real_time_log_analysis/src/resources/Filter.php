<?php

class Filter
{
    protected $m_env = array();

    public function __construct($conf)
    {
    }

    public function doFilter($param)
    {
        return $param;      //do not process the param defaultly.
    }

    public static function createFilter($type,$conf)
    {
        $class_name = $type."Filter";
        if(class_exists($class_name))
            return new $class_name($conf);
        return new Filter($conf);
    }
}



class CloudMarkFilter extends Filter
{
    public function __construct($conf)
    {
        $this->m_env['product_combo_list'] = $conf['product_combo_list'];
    }

    //filter the hadoop log in advance.
    public function doFilter($param)
    {
        //need to check the returning process... 
        if($this->hasMarkType($param['request_param']))
            return array($param['line']);
        else 
            return null;
    }

    protected function hasMarkType($request_param)
    {
       if(empty($request_param) || empty($request_param['phone_call_query']['calls']) || !is_array($request_param['phone_call_query']['calls'])) 
           return false;

        foreach($request_param['phone_call_query']['calls'] as $call_info)
        {
            if(!empty($call_info['new_marked_type']) || !empty($call_info['old_marked_type']))
                return true;

            if(!empty($call_info['extra_uploaded_info'])) {
                foreach($call_info['extra_uploaded_info'] as $ext_info) {
                    if($ext_info['key'] === 'extra_marks') {
                        if(empty($ext_info['val']))
                            continue;

                        return true;
                    }
                }
            }
        }
        return false;
    }
}

class TuCaoFilter extends Filter
{
    //public function __construct($conf)
    //{
    //    $this->m_env['product_combo_list'] = $conf['product_combo_list'];
    //}

    //filter the hadoop log in advance.
    public function doFilter($param)
    {
        $output = array(); 
    
        //the var is not used at present
        $has_tucao_info = $this->hasTucaoInfo($param['line']);
    
        $request_param = $param['request_param']; 
        if(empty($request_param) || empty($request_param['phone_call_query']['calls']) || !is_array($request_param['phone_call_query']['calls']))
            return $output;
        
        $qid = empty($request_param['qid']) ? "" : $request_param['qid'];       // need to adjust,marker_qid  may be the accurate value.

        foreach($request_param['phone_call_query']['calls'] as $call_info)
        {
            if(empty($call_info['exts']))
                continue;
            
            $tucao_content = null;
            foreach($call_info['exts'] as $ext_info)
            {
                if($ext_info['key'] === 'marker_tucao')
                {
                    $tucao_content = $ext_info['val'];
                    break;      // need to check if marker_tucao is unique.
                }
            }
            if(empty($tucao_content))
                continue;
            $tucao_content = base64_encode($tucao_content);

            // $call_info['peer_phone_num_md5']['type'] == 5  is telephone number
            if(empty($call_info['peer_phone_num_md5']['raw_text']) || $call_info['peer_phone_num_md5']['type'] !== 5)
                continue;
            $phone = $call_info['peer_phone_num_md5']['raw_text'];

            //need to confirm the function of mark_type   
            $mark_type = isset($call_info['new_marked_type']) ? $call_info['new_marked_type'] : ""; 

            $str = $phone . "," . $tucao_content . "," . $qid . "|" . $param['ip']  . "," . $mark_type . "," .  $param['request_time'] . ",phone" . "," . $request_param['mid'];
            $output[] = $str;
        }
        
        return $output;
    }

    protected function hasTucaoInfo($line)
    {
        // === is right
        if(strpos($line,'marker_tucao') === false) 
            return false;
        else 
            return true;
    }
}













