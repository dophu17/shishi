<?php
App::uses('AppHelper', 'View/Helper');

class UrlHelper extends AppHelper {
  /**
* Prep URL
*
* Simply adds the http:// part if no scheme is included
*
* @param string the URL
* @return string
*/
  function prepUrl($str = '', $includeWww = false)
  {
    $prefix = 'http://';
    $prefix = $includeWww? $prefix . 'www.': $prefix;
    if ($str === $prefix OR $str === '') {
      return '';
    }

    $url = parse_url($str);

    if ( ! $url OR ! isset($url['scheme'])) {
      return $prefix.$str;
    }

    return $str;
  }
}