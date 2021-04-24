<?php

namespace Drupal\xyfer_weather_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class XyferWeatherForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'xyfer_weather_api.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'weather_api_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('xyfer_weather_api.settings');

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Weather API Key'),
      '#default_value' => $config->get('api_key'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('xyfer_weather_api.settings')
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();
  }
}
