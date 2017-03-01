<?php
/**
 * Created by PhpStorm.
 * User: vincentlambert
 * Date: 27/02/2016
 * Time: 18:03
 */

namespace Drupal\performance_benchmark\Controller;

use Drupal\Core\Controller\ControllerBase;

class PerformanceBenchmarkController extends ControllerBase {

  public function overview() {
    $build = array();
    $build['presentation'] = array(
      '#type' => 'markup',
      '#markup' => $this->t('This PHP performance benchmark script calculates benchmark speeds (PHP execution times). The script performs some simple mathematics and string manipulating functions repetitively, and records the execution time.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>'
    );
    $build['info_system'] = array(
      '#theme' => 'item_list',
      '#title' => $this->t('System information :'),
      '#items' => array(
        t('OS') . ' : ' . php_uname(),
        t('PHP version') . ' : ' . phpversion(),
        t('System load average') . ' : ' . implode(", ", sys_getloadavg()),
        t('Memory limit') . ' : ' . ini_get('memory_limit'),
        t('Memory allocated') . ' : ' . $this->convert(memory_get_usage()),
        t('Memory allocated from system') . ' : ' . $this->convert(memory_get_usage(true)),
        t('Peak memory  allocated') . ' : ' . $this->convert(memory_get_peak_usage()),
        t('Peak memory allocated from system') . ' : ' . $this->convert(memory_get_peak_usage(true))
      )
    );
    $build['performance_benchmark_control_form'] = \Drupal::formBuilder()->getForm('Drupal\performance_benchmark\Form\ControlForm');
    return $build;
  }

  protected function convert($size) {
    $unit=array('b','Kb','Mb','Gb','Tb','Pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
  }

}