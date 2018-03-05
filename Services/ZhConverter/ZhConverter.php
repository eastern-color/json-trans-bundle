<?php

namespace EasternColor\JsonTransBundle\Services\ZhConverter;

use DreamsDrive\ZhConv;

/**
 * @see https://github.com/wikimedia/mediawiki/tree/master/languages/data
 *
 */
class ZhConverter {

    public function __construct()
    {
        include_once __DIR__.'/ZhConversion.php';
// dump(class_exists('MediaWiki\Languages\Data\ZhConversion'));
        include_once __DIR__.'/zh_table.inc.php';

        $this->zh2Hant = $zh2Hant;
        $this->zh2Hans = $zh2Hans;
        $this->zh2TW = $zh2TW;
        $this->zh2HK = $zh2HK;
        $this->zh2CN = $zh2CN;
        $this->zh2SG = $zh2SG;
    }

    protected static $code = array(
        'Hant' => array('CN','SG'),
        'Hans' => array('HK','TW'),
    );

    public function translate($text, $target)
    {
      if ( $target == 'Hant' ){
    		$text = strtr( $text, \MediaWiki\Languages\Data\ZhConversion::$zh2Hant );
      }else if ( $target == 'Hans' ){
    		$text = strtr( $text, \MediaWiki\Languages\Data\ZhConversion::$zh2Hans );
      }else if ( $target == 'TW' ){
    		$text = strtr( $text, \MediaWiki\Languages\Data\ZhConversion::$zh2Hant );
    		$text = strtr( $text, \MediaWiki\Languages\Data\ZhConversion::$zh2TW );
      }else if ( $target == 'HK' ){
    		$text = strtr( $text, \MediaWiki\Languages\Data\ZhConversion::$zh2Hant );
    		$text = strtr( $text, \MediaWiki\Languages\Data\ZhConversion::$zh2HK );
      }else if ( $target == 'CN' ){
    		$text = strtr( $text, \MediaWiki\Languages\Data\ZhConversion::$zh2Hans );
    		$text = strtr( $text, \MediaWiki\Languages\Data\ZhConversion::$zh2CN );
      }else{
        throw new \RuntimeException("unkown translate target");}
  		return $text;
    }

    public function get($in,$out,$str)
    {
        $new_str = $str;
        foreach(self::$code as $han => $code){
            if(in_array($in, $code)){
                // include('zh_table.inc.php');
                $extra = $this->{'zh2'.$out};
                foreach($extra as $k=>$v){
                    $new_str = str_replace($k,$v,$new_str);
                }
                foreach($this->{'zh2'.$han} as $k=>$v){
                    $new_str = str_replace($k,$v,$new_str);
                }
            }
        }

        return $new_str;
    }
}
