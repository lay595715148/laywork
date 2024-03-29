<?php
class Security extends Base {
    private static $key = 'MsuY7U0iF=s=';
	/**
	 * @param $txt 要加密字符串
	 * @param $key 密钥
	 */
    public static function encrypt($txt,$key = '') {
		if(!$key) {
			$key = self::$key;
		}
	    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	    $ikey ="-bear-x6g6ZWm2G9gq3kRIxsZ6_vr0Bo.pOrm";
	    $nh1 = rand(0,64);
	    $nh2 = rand(0,64);
	    $nh3 = rand(0,64);
	    $ch1 = $chars{$nh1};
	    $ch2 = $chars{$nh2};
	    $ch3 = $chars{$nh3};
	    $nhnum = $nh1 + $nh2 + $nh3;
	    $knum = 0;$i = 0;
	    while(isset($key{$i})) $knum +=ord($key{$i++});

	    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
	    $txt = base64_encode($txt);
	    $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
	    $tmp = '';
	    $j=0;$k = 0;
	    $tlen = strlen($txt);
	    $klen = strlen($mdKey);
	    for ($i=0; $i<$tlen; $i++) {
			$k = $k == $klen ? 0 : $k;
			$j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
			$tmp .= $chars{$j};
	    }

	    $tmplen = strlen($tmp);
	    $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
	    $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
	    $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
	    return $tmp;
    }

    /**
	 * @param $txt 要解密字符串
	 * @param $key 密钥
	 */
    public static function decrypt($txt,$key = '') {
    	if(!$key) {
			$key = self::$key;
		}

	    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	    $ikey = "-bear-x6g6ZWm2G9gq3kRIxsZ6_vr0Bo.pOrm";
	    $knum = 0;$i = 0;
	    $tlen = strlen($txt);
	    while(isset($key{$i})) $knum +=ord($key{$i++});

	    $ch1 = $txt{$knum % $tlen};
	    $nh1 = strpos($chars,$ch1);
	    $txt = substr_replace($txt,'',$knum % $tlen--,1);
	    $ch2 = $txt{$nh1 % $tlen};
	    $nh2 = strpos($chars,$ch2);
	    $txt = substr_replace($txt,'',$nh1 % $tlen--,1);
	    $ch3 = $txt{$nh2 % $tlen};
	    $nh3 = strpos($chars,$ch3);
	    $txt = substr_replace($txt,'',$nh2 % $tlen--,1);
	    $nhnum = $nh1 + $nh2 + $nh3;
	    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
	    $tmp = '';
	    $j=0; $k = 0;
	    $tlen = strlen($txt);
	    $klen = strlen($mdKey);

	    for ($i=0; $i<$tlen; $i++) {
			$k = $k == $klen ? 0 : $k;
			$j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
			while ($j<0) $j+=64;
			$tmp .= $chars{$j};
	    }

    	$tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
   		return trim(base64_decode($tmp));
    }
    
	/**
     * ldap中md5方式加密字符串
	 * @param $txt 要加密字符串
	 * @param $sign 是否添加前缀
	 */
    public static function ldapmd5($txt,$sign = true) {
        return (($sign)?'{md5}':''). base64_encode(pack( 'H*',md5($txt)));
    }
    /**
     * 随机生成一段字符串
     */
    public static function random() {
        return base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid()));
    }
    /**
     * 随机生成一段md5加密字符串
     */
    public static function randommd5() {
        return md5(base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid())));
    }
}
?>
