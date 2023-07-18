<?php

namespace ghopunk\Helpers;

class Spintax{
	
    public function process($text){
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            $text
        );
    }

    public function replace($text){
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
	
	//spintax without include spin text in <script> and <style>
	public function cleanSpintax($text){
		$match = array();
		if ( strpos($text, '<style') !== false ){
			preg_match_all("/<style\\b[^>]*>(.*?)<\\/style>/s", $text, $style); //match style
			if( !empty($style[0]) ){
				$match = array_merge($match,$style[0]);
			}
		}
		if ( strpos($text, '<script') !== false ){
			preg_match_all("/<script\\b[^>]*>(.*?)<\\/script>/s", $text, $script); //match script
			if( !empty($script[0]) ){
				$match = array_merge($match,$script[0]);
			}
		}
		if( !empty($match) ){
			$match = array_unique($match);
			foreach( $match as $key=>$val ){
				$text = str_replace($val, '#$~'.$key.'~$#', $text); //add code change 
			}
		}
		$text = $this->process($text);
		if( !empty($match) ){
			foreach($match as $key=>$val){
				$text = str_replace('#$~'.$key.'~$#', $val, $text); //remove code change 
			}
		}
		return $text;
	}
}
