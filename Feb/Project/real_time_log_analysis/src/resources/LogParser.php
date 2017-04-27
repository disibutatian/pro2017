<?php 

class LogParser
{
    public function parseLine($line)
    {
        $line = trim($line);
        return $line;       //need to watch the post-processing.
    }

    public static function createLogParser($type, $conf)
    {
        $class_name = $type . "LogParser";
        if(class_exists($class_name))
            return new $class_name($conf);
        return new LogParser($conf);
    }
}

class PhoneCallLogParser
{
    public function parseLine($line)
    {
        $line = trim($line);
        $arr = explode("----", $line);
        if(count($arr) < 3)
            return false;
        
        $server_param = json_decode(htmlspecialchars_decode($arr[0]), true);
        if(empty($server_param))
        {   
            $tail_2_char = substr($arr[0], -2, 1);  // may be adapt the old version...
            if($tail_2_char == ',')
                $arr[0] = substr_replace($arr[0], "", -2 ,1);
            $server_param = json_decode(htmlspecialchars_decode($arr[0]), true);
        }   
        $request_param = json_decode(htmlspecialchars_decode($arr[1]), true);
        $response_param = json_decode(htmlspecialchars_decode($arr[2]), true);
            
        $param['line'] = $line;
        $param['request_time'] = intval($server_param['time']);
        $param['ip'] = $server_param['ip'];
        $param['product'] = $request_param['product'];
        $param['combo'] = $request_param['combo'];
        $param['request_param'] = $request_param;
        $param['response_param'] = $response_param;
        return $param;
    }
}
