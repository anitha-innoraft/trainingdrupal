<?php

namespace Drupal\mymodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use GuzzleHttp\Client;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Temperature' Block.
 *
 * @Block(
 *   id = "temperature_block",
 *   admin_label = @Translation("Temperature block"),
 * )
 */
class Temperatureblock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Object variable.
   *
   * @var object
   */
  protected $configfactory;

  /**
   * Construct method.
   *
   * @param array $configuration
   *   It return value of array.
   * @param string $plugin_id
   *   It return plugin id.
   * @param mixed $plugin_definition
   *   It returns the plugin definition.
   * @param Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   It returns the object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->configfactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
    );
  }

  /**
   * Build function to render outut of the block.
   *
   * @return array
   *   It will return the array.
   */
  public function build() {
    $config = $this->configfactory->get('mymodule.settings');

    $country = $config->get('country');
    $city = $config->get('city');
    $apikey = $config->get('apikey');
    $apiendpoint = $config->get('apiendpoint');
    $client = new Client();
    $service_url = "https://" . $apiendpoint . '?q=' . $city . ',' . $country . '&appid=' . $apikey;
    $response = $client->get($service_url);
    $output = json_decode($response->getBody(), TRUE);
    // print_r($output);exit;
    // $curl = curl_init($service_url);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($curl, CURLOPT_POST, FALSE);
    // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    // $curl_response = curl_exec($curl);
    // curl_close($curl);
    // $output = json_decode($curl_response);
    return [
      '#markup' => $this->t('The temperature of the @city is : @numberK',
      ['@city' => $city, '@number' => $output['main']['temp']]),
    ];
  }

  /**
   * Disable cache on this block function.
   *
   * @return int
   *   Return cache is enbled or not.
   */
  public function getCacheMaxAge() {
    // If you want to disable caching for this block.
    return 0;
  }

}
