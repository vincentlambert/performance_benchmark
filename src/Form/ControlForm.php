<?php
/**
 * Created by PhpStorm.
 * User: vincentlambert
 * Date: 27/02/2016
 * Time: 16:49
 */

namespace Drupal\performance_benchmark\Form;

use Drupal\performance_benchmark\Benchmark\Benchmark;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class ControlForm extends FormBase{

  public function getFormId() {
    return 'performance_benchmark.control_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {


    $performance_benchmark_results = $form_state->getValue('performance_benchmark_results');
    if(is_null($performance_benchmark_results)) {
      $build['performance_benchmark_control_form_description'] = array (
        '#type' => 'details',
        '#title' => $this->t('Execute benchmark'),
        '#open' => TRUE
      );
      $build['performance_benchmark_control_form_description']['description'] = array (
        '#type' => 'markup',
        '#markup' => $this->t('This will run benchmark script. It can take e few seconds.'),
        '#prefix' => '<p>',
        '#suffix' => '</p>'
      );
      $build['performance_benchmark_control_form_description']['run'] = array (
        '#type' => 'submit',
        '#value' => $this->t('Run')
      );
    } else {
      $build['performance_benchmark_control_form_description'] = array (
        '#type' => 'details',
        '#title' => $this->t('Benchmark results'),
        '#open' => TRUE
      );
      $build['performance_benchmark_control_form_description']['results'] = array (
        '#type' => 'markup',
        '#markup' => $performance_benchmark_results,
        '#prefix' => '<h3>',
        '#suffix' => '</h3>'
      );
    }

    return $build;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();

    $results = Benchmark::run(FALSE);

    $form_state->setValue('performance_benchmark_results', $results);

    drupal_set_message($this->t('Performance benchmark done.'));
  }
}