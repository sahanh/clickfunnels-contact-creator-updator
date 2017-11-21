<?php
require __DIR__.'/vendor/autoload.php';

use Goutte\Client;
use GuzzleHttp\Exception\ConnectException;

$client    = new Client();

/**
 * Direct URL of the funnel step
 */
$funnel_step_url = 'https://account.ssclickfunnels.com/contact-updateenfw2b4s';

/**
 * Get JSON input. Since we'll be working with webhooks of external systems
 * I am taking json as the input
 */
$input    = json_decode(file_get_contents('php://input'), true);

/**
 * Extract parameters
 */
$fields = [
    'contact[email]' => array_get($input, "current.cc_email"),
    'contact[name]' => array_get($input, "current.name"),
    'contact[first_name]' => '',
    'contact[last_name]' => '',
    'contact[email]' => '',
    'contact[phone]' => '',
    'contact[address]' => '',
    'contact[city]' => '',
    'contact[state]' => '',
    'contact[country]' => '',
    'contact[zip]' => ''
];

try {

    $crawler = $client->request('GET', $funnel_step_url);
    /**
     * In funnel step page, we are looking for a form
     */
    $form    = $crawler->filter('form')->form();

    /**
     * Submitting the form with above fields
     */
    $crawler = $client->submit($form, $fields);

} catch (InvalidArgumentException $e) {
   
    if ($e->getMessage() == 'The current node list is empty.') {
        echo "Got an unexpected page.";
    }

} catch (Exception $e) {
    echo $e->getMessage();
}

