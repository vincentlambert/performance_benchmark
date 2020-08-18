<?php

namespace Drupal\performance_benchmark\Form;

use Drupal\performance_benchmark\Benchmark\Benchmark;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Benchmark form.
 */
class BenchmarkForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'performance_benchmark.control_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Add server informations.
    $build = $this->getServerInfo();

    // If a benchmark has run.
    if ($form_state->getValue('run') != NULL) {
      $build['performance_benchmark_control_form_description'] = [
        '#type' => 'details',
        '#title' => $this->t('Benchmark results'),
        '#open' => TRUE,
      ];
      $build['performance_benchmark_control_form_description']['information'] = [
        '#type' => 'markup',
        '#markup' => 'From Alessandro Torrisi PHP Benchmark Performance Script ©2010 Code24 BV.',
      ];
      $build['performance_benchmark_control_form_description']['results'] = $this->runBenchmark();
    }
    else {
      $build['performance_benchmark_control_form_description'] = [
        '#type' => 'details',
        '#title' => $this->t('Execute benchmark'),
        '#open' => TRUE,
      ];
      $build['performance_benchmark_control_form_description']['description'] = [
        '#type' => 'markup',
        '#markup' => $this->t('This will run benchmark script. It can take a few seconds.'),
        '#prefix' => '<p>',
        '#suffix' => '</p>',
      ];
      $build['performance_benchmark_control_form_description']['run'] = [
        '#type' => 'submit',
        '#value' => $this->t('Run'),
      ];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();
    \Drupal::messenger()->addMessage($this->t('Performance benchmark done.'));
  }

  /**
   * Run Benchmark.
   *
   * @return array
   *   Form element.
   */
  private function runBenchmark() {
    $results = Benchmark::run();

    $table = [
      '#type' => 'table',
      '#header' => [
        $this->t('Test name'),
        $this->t('Test duration'),
      ],
    ];

    $i = 0;
    foreach ($results as $label => $duration) {
      $table[$i]['name'] = [
        '#type' => 'markup',
        '#markup' => $label,
      ];
      $table[$i]['duration'] = [
        '#type' => 'markup',
        '#markup' => $duration . ' sec',
      ];

      $i++;
    }

    return $table;
  }

  /**
   * Prepare Server informations.
   *
   * @return array
   *   Form element.
   */
  private function getServerInfo() {
    $build = [];
    $build['presentation'] = [
      '#type' => 'markup',
      '#markup' => $this->t('This PHP performance benchmark script calculates benchmark speeds (PHP execution times). The script performs some simple mathematics and string manipulating functions repetitively, and records the execution time.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    ];

    $build['info_system'] = [
      '#theme' => 'item_list',
      '#title' => $this->t('System information :'),
      '#items' => [
        $this->t('OS : %value', ['%value' => php_uname()]),
        $this->t('PHP version : %value', ['%value' => phpversion()]),
        $this->t('System load average : %value', ['%value' => implode(", ", sys_getloadavg())]),
        $this->t('Memory limit : %value', ['%value' => ini_get('memory_limit')]),
        $this->t('Memory allocated : %value', ['%value' => $this->convert(memory_get_usage())]),
        $this->t('Memory allocated from system : %value', ['%value' => $this->convert(memory_get_usage(TRUE))]),
        $this->t('Peak memory  allocated : %value', ['%value' => $this->convert(memory_get_peak_usage())]),
        $this->t('Peak memory allocated from system : %value', ['%value' => $this->convert(memory_get_peak_usage(TRUE))]),
      ],
    ];
    return $build;
  }

  /**
   * Convert units.
   *
   * @param int $size
   *   Size of data.
   *
   * @return string
   *   Converted size of data.
   */
  protected function convert(int $size) {
    $unit = ['b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb'];
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
  }

}
