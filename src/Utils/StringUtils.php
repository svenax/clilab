<?php
namespace CliLab\Utils;

class StringUtils {
  public static function truncate($string, $limit, $break=' ', $pad='…')
  {
    if (strlen($string) <= $limit) return $string;

    $string = substr($string, 0, $limit);
    if (false !== ($breakpoint = strrpos($string, $break))) {
      $string = substr($string, 0, $breakpoint);
    }

    return $string . $pad;
  }
}
