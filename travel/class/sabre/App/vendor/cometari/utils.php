<?php

class Utils
{

    static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    static function in($haystack, $needle)
    {
      if(0){
      echo $haystack, $needle;
      echo '<br />';
      var_dump(preg_match('/'.$needle.'/i',$haystack));
      echo '<br />';
      }
        return preg_match('/'.$needle.'/i',$haystack);
    }
}
