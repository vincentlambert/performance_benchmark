<?php

namespace Drupal\performance_benchmark\Benchmark;

/**
 * Class Benchmark.
 *
 *                     PHP Benchmark Performance Script                  #
 *                         © 2010 Code24 BV                              #
 *                                                                       #
 *  Author      : Alessandro Torrisi                                     #
 *  Company     : Code24 BV, The Netherlands                             #
 *  Date        : July 31, 2010                                          #
 *  version     : 1.0.1                                                  #
 * License     : Creative Commons CC-BY license                          #
 * Website     : http://www.php-benchmark-script.com                     #
 */
class Benchmark {

  /**
   * Run Maths test.
   *
   * @param int $count
   *   Number of runs.
   *
   * @return string
   *   Execution time.
   */
  private static function testMaths($count = 140000) {
    // 140000
    $time_start = microtime(TRUE);
    $mathFunctions = [
      "abs",
      "acos",
      "asin",
      "atan",
      "bindec",
      "floor",
      "exp",
      "sin",
      "tan",
      "pi",
      "is_finite",
      "is_nan",
      "sqrt",
    ];
    foreach ($mathFunctions as $key => $function) {
      if (!function_exists($function)) {
        unset($mathFunctions[$key]);
      }
    }
    for ($i = 0; $i < $count; $i++) {
      foreach ($mathFunctions as $function) {
        $r = call_user_func_array($function, [$i]);
      }
    }
    return number_format(microtime(TRUE) - $time_start, 3);
  }

  /**
   * Run String Manipulation test.
   *
   * @param int $count
   *   Number of runs.
   *
   * @return string
   *   Excecution time.
   */
  private static function testStringManipulation($count = 130000) {
    // 130000
    $time_start = microtime(TRUE);
    $stringFunctions = [
      "addslashes",
      "chunk_split",
      "metaphone",
      "strip_tags",
      "md5",
      "sha1",
      "strtoupper",
      "strtolower",
      "strrev",
      "strlen",
      "soundex",
      "ord",
    ];
    foreach ($stringFunctions as $key => $function) {
      if (!function_exists($function)) {
        unset($stringFunctions[$key]);
      }
    }
    $string = "the quick brown fox jumps over the lazy dog";
    for ($i = 0; $i < $count; $i++) {
      foreach ($stringFunctions as $function) {
        $r = call_user_func_array($function, [$string]);
      }
    }
    return number_format(microtime(TRUE) - $time_start, 3);
  }

  /**
   * Run test loops.
   *
   * @param int $count
   *   Number of runs.
   *
   * @return string
   *   Excecution time.
   */
  private static function testLoops($count = 19000000) {
    // 19000000
    $time_start = microtime(TRUE);
    for ($i = 0; $i < $count; ++$i);
    $i = 0; while ($i < $count) {
      ++$i;
    }
    return number_format(microtime(TRUE) - $time_start, 3);
  }

  /**
   * Run If/Else test.
   *
   * @param int $count
   *   Number of runs.
   *
   * @return string
   *   Excecution time.
   */
  private static function testIfElse($count = 9000000) {
    // 9000000
    $time_start = microtime(TRUE);
    for ($i = 0; $i < $count; $i++) {
      if ($i == -1) {
      }
      elseif ($i == -2) {
      }
      elseif ($i == -3) {
      }
    }
    return number_format(microtime(TRUE) - $time_start, 3);
  }

  /**
   * Main run method.
   *
   * @return array
   *   Test results.
   */
  public static function run() {

    $tests = [
      'Maths test' => "testMaths",
      'String Manipulation test' => "testStringManipulation",
      'Loops test' => "testLoops",
      'If/Else test' => "testIfElse",
    ];

    $total = 0;
    foreach ($tests as $label => $callback) {
      $result = self::$callback();
      $total += $result;
      $output[$label] = $result;
    }
    $output['Total duration'] = $total;

    return $output;
  }

}
