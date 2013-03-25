<?php
/**
 * 
 */
class Transfer extends Base {
    /**
     * xml format string to php array
     * 
     * @param string $xml xml format string or xml file path string
     * @return array
     */
    public static function xml2PHPArray($xml,$type = 1) {
        if($type) {
            $xmlstr = simplexml_load_file($xml);
        } else {
            $xmlstr = simplexml_load_string($xml);
        }
        $json = json_encode($xmlstr);
        return json_decode($json, true);

        //serialize simplexml bug
        if($type) {
            $xmlstr = simplexml_load_file($xml);
        } else {
            $xmlstr = simplexml_load_string($xml);
        }
        $arr = array();
        $str = serialize($xmlstr);//serialize()  产生一个可存储的值的表示 
        $str = str_replace('O:16:"SimpleXMLElement"', 'a', $str);
        $arr = unserialize($str); //unserialize()  从已存储的表示中创建 PHP 的值
        return $arr;

        //bug
        $xmlary  = array();
        $reels   = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*)<\/\s*\\1\s*>)/s';
        $reattrs = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';

        preg_match_all($reels, $xml, $elements);print_r($elements);

        foreach ($elements[1] as $ie => $xx) {
            $xmlary[$ie]["name"] = $elements[1][$ie];
            if ($attributes = trim($elements[2][$ie])) {
                preg_match_all($reattrs, $attributes, $att);
                foreach ($att[1] as $ia => $xx) {
                    $xmlary[$ie]["attributes"][$att[1][$ia]] = $att[2][$ia];
                }
            }
            $cdend = strpos($elements[3][$ie], "<");
            if ($cdend > 0) {
                $xmlary[$ie]["text"] = substr($elements[3][$ie], 0, $cdend - 1);
            }
            if (preg_match($reels, $elements[3][$ie])) {
                $xmlary[$ie]["elements"] = self::xml2PHPArray($elements[3][$ie]);
            } else if ($elements[3][$ie]) {
                $xmlary[$ie]["text"] = $elements[3][$ie];
            }
        }

        return $xmlary;
    }
    /**
     * php array to json content
     * 
     * @return json content
     */
    public static function array2JsonContent($arr) {
        return json_encode($arr);
    }
    /**
     * php array to php content
     * 
     * @return php content
     */
    public static function array2PHPContent($arr) {
        $r = '<?php return ';
        self::a2s($r,$arr);
        $r .= ';?>';
        return $r;
    }
    /**
     * array $a to string $r
     * 
     * @return void
     */
    private static function a2s(&$r,array &$a) {
        $f = false;
        $i=0;
        $r.= 'array('."\n";
        foreach ($a as $k=>$v) {
            if($f)
            $r.=',';
            $j = is_numeric($k);
            self::o2s($r,$k,$v,$i,$j);
            $f=true;
            if($j && $k>=$i)
                $i=$k+1;
        }
        $r.=')'."\n";
    }
    private static function o2s(&$r,$k,$v,$i,$j) {
        if($k!==$i) {
            if($j)
                $r.="$k=>";
            else
                $r.="'$k'=>";
        }
        if(is_array($v))
            self::a2s($r,$v);
        else if(is_numeric($v))
            $r.=$v;
        else
            $r.="'".str_replace(array("\\","'"),array("\\\\","\'"),$v)."'";
    }
}
?>
