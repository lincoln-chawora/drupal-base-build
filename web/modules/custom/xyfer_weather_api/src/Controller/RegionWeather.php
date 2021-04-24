<?php

namespace Drupal\xyfer_weather_api\Controller;

use Drupal\Component\Serialization\SerializationInterface;
use GuzzleHttp\ClientInterface;

class RegionWeather {

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Drupal\Component\Serialization\SerializationInterface definition.
   *
   * @var \Drupal\Component\Serialization\SerializationInterface
   */
  protected $serializationJson;

  /**
   * Constructs a new RegionWeather object.
   */
  public function __construct(ClientInterface $http_client, SerializationInterface $serialization_json) {
    $this->httpClient = $http_client;
    $this->serializationJson = $serialization_json;
  }

  /**
   * Get region specific weather data.
   *
   * For additional information the api, see the weatherapi documentation here:
   * https://www.weatherapi.com/docs/
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getRegionWeather($query) {
    $config = \Drupal::config('xyfer_weather_api.settings');
    $api_key = $config->get('api_key');
    $url = 'http://api.weatherapi.com/v1/current.json?key=' . $api_key . '&q=' . $query . '&aqi=no';

    try {
      if ($response = $this->httpClient->request('GET', $url)) {
        if ($response->getStatusCode() == 200) {
          if ($decoded = $this->serializationJson->decode($response->getBody())) {
            if (isset($decoded['current'])) {
              $region_data = $decoded;
            }
          }
        }
      }
    }
    catch (\Exception $e) {
      $this->messenger()
        ->addError('No weather data available for this region, please try again later.');
      $this->messenger()->addError($e->getMessage());
    }
    return $region_data;
  }

  /**
   * Gets the messenger.
   *
   * @return \Drupal\Core\Messenger\MessengerInterface
   *   The messenger.
   */
  public function messenger() {
    if (!isset($this->messenger)) {
      $this->messenger = \Drupal::messenger();
    }
    return $this->messenger;
  }
}
