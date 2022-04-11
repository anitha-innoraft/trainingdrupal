<?php

namespace Drupal\mymodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\core\Plugin\ContainerFactoryPluginInterface;
use Drupal\core\Logger\LoggerChannelFactoryInterface;

const APIURL = "https://api.tiingo.com/tiingo/daily/";
/**
 * Provides a 'Stock' Block.
 *
 * @Block(
 *   id = "stock_block",
 *   admin_label = @Translation("Stock block"),
 * )
 */
class Stockblock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * An http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Expection Handling.
   *
   * @var \Drupal\core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client'),
      $container->get('logger.factory')
    );
  }

  /**
   * Construct.
   *
   * @param array $configuration
   *   Plugin Configurations.
   * @param mixed $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \GuzzleHttp\Client $http_client
   *   An HTTP client.
   * @param Drupal\core\Logger\LoggerChannelFactoryInterface $logger
   *   Exception handling variable.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Client $http_client, LoggerChannelFactoryInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpclient = $http_client;
    $this->logger = $logger;
  }

  /**
   * Method to return the values to block.
   *
   * @return array
   *   It will return the array to render elements.
   */
  public function build() {
    $config = $this->getConfiguration();

    $company_symbol = $config['company_symbol'];
    $start_date = empty($config['start_date']) ?: date('Y-m-d');
    $end_date = empty($config['end_date']) ?: date('Y-m-d');
    // $start_date ='2022-2-15';
    // $end_date = '2022-2-15';
    $service_url = APIURL . $company_symbol . "?token=34f412d51db4046a81f4180aad2233c41df5d3b1";
    $output = $this->curlResponse($service_url);

    $price_url = APIURL . $company_symbol . "/prices?startDate=" . $start_date . "&endDate=" . $end_date . "&token=34f412d51db4046a81f4180aad2233c41df5d3b1";
    $priceoutput = $this->curlResponse($price_url);

    $pricedetails = "";
    foreach ($priceoutput as $pricedata) {
      $pricedetails .= "<strong> Date: </strong>" . date('Y-m-d', strtotime($pricedata['date'])) . "<br/>";
      $pricedetails .= "<strong>Highest Price: </strong>" . $pricedata['high'] . "<br/>";
      $pricedetails .= "<strong>Lowest Price: </strong>" . $pricedata['low'] . "<br/>";

    }
    return [
      '#theme' => 'stock_template',
      '#stockname' => $output['name'],
      '#stockdesc' => $output['description'],
      '#pricedetails' => $pricedetails,
    ];
  }

  /**
   * Block form function is used to create a form.
   *
   * @param object $form
   *   We will set the field in form variable.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   It will retrieve the value of form.
   *
   * @return array
   *   Return the form fields.
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $form['company_symbol'] = [
      '#type' => 'textfield',
      '#title' => t('Company'),
      '#default_value' => $config['company_symbol'] ?? '',
    ];
    $form['start_date'] = [
      '#type' => 'textfield',
      '#title' => t('Start date'),
      '#default_value' => $config['start_date'] ?? '',
    ];
    $form['end_date'] = [
      '#type' => 'textfield',
      '#title' => t('End date'),
      '#default_value' => $config['end_date'] ?? '',
    ];

    return $form;
  }

  /**
   * Block submit function.
   *
   * @param object $form
   *   We will set the field in form variable.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   It will retrieve the value of form.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('company_symbol', $form_state->getValue('company_symbol'));
    $this->setConfigurationValue('start_date', $form_state->getValue('start_date'));
    $this->setConfigurationValue('end_date', $form_state->getValue('end_date'));
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

  /**
   * Curl function.
   *
   * @param string $service_url
   *   Return string.
   *
   * @return objectarray
   *   Return objectarray.
   */
  public function curlResponse($service_url) {
    try {
      $response = $this->httpclient->get($service_url);
      $output = json_decode($response->getBody(), TRUE);
      return $output;
    }
    catch (\Exception $e) {
      $this->logger->get('widget')->error($e->getMessage());
    }
  }

}
