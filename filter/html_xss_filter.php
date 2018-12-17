<?php

/**
 * @desc html xss filter 
 * @v0.1: 2018-10-24 only xss check
 * @author: imfeiyan@gmail.com
 */
class Xss
{
    public $_allow_html_tags = array(
        'table', 'tbody', 'thead', 'tr', 'td',
        'ul', 'ol', 'li',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'p', 'span', 'strong',
        'img',
        'br', 'div', 'caption',
    );

    public $_allow_attrs = array(
        'title', 'alt', 'src', 'href', 'class',
    );

    /**
     * @desc step one: 判断是否允许的html标签
     * @desc step two: 判断是否允许的html属性
     */
    public function xss_check($str){
        $str = urldecode($str);
        $str_strip = strip_tags($str, '<'.implode('><', $this->_allow_html_tags).'>');
        if( $str_strip!=$str ){
            return false;
        }
        foreach( $this->_allow_html_tags as $html_tag ){
            $pattern = '/<'.$html_tag.'[^>]+>/';
            preg_match_all($pattern, $str_strip, $tags);
            if( isset($tags[0]) && !empty($tags[0]) ){
                foreach( $tags[0] as $tag ){
                    preg_match_all('/\s+(\S*?)\s*\=/', $tag, $attrs);
                    if( isset($attrs[1]) && !empty($attrs[1]) ){
                        foreach( $attrs[1] as $attr ){
                            if( !(in_array($attr, $this->_allow_attrs) || stripos($attr, 'data-')===0) ){
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return true;

    }
}
