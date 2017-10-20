<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @package    TIG_Postcode
 * @copyright  Copyright (c) 2012 Total Internet Group (http://www.totalinternetgroup.nl)
 * @author     Total Internet Group (http://www.totalinternetgroup.nl)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * TIG PostCode Address model
 *
 * @package    TIG_PostCode
 * @copyright  Copyright (c) 2011 Total Internet Group (http://www.totalinternetgroup.nl)
 * @author     Total Internet Group (http://www.totalinternetgroup.nl)
 */
class TIG_PostCode_Model_Address
{
  /**
   * cURL and authentication options
   * @var array
   */
    protected $_options = array(
        'client_id'	=> '',
        'secure_code'	=> '',
        'failonerror' => 1,
        'returntransfer'	=> 1,
        'connecttimeout'	=> 5
    );

    /**
     * Log is enabled flag
     * @var boolean
     */
    private $_logIsEnabled = false;

    /**
     * Initialization
     */
    public function __construct()
    {
        $this->_options['client_id'] = urlencode(Mage::getStoreConfig('tig_postcode/general/client_id'));
        $this->_options['secure_code'] =  urlencode(Mage::getStoreConfig('tig_postcode/general/secure_code'));
        $this->_logIsEnabled = Mage::getStoreConfig('tig_postcode/log/log_is_enabled');
    }

    /**
     * Returned City and Street by Zip Code and House Number
     *
     * @param string $postcode
     * @param string $housenumber
     */
    public function search($postcode, $housenumber) {
        $url = 'https://postcode.tig.nl/api/v3/json/getAddress/index.php?' . http_build_query(
                array(
                    'postcode'    => $postcode,
                    'huisnummer'  => $housenumber,
                    'client_id'   => $this->_options['client_id'],
                    'secure_code' => $this->_options['secure_code'],
                    'domain'      => $_SERVER['HTTP_HOST'],
                    'remote_ip'   => $_SERVER['REMOTE_ADDR']
                )
            );

        try {
            if (!function_exists('curl_init')) {
                throw new Exception('сURL library is not installed');
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, $this->_options['failonerror']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->_options['returntransfer']);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_options['connecttimeout']);
            $postcode_answer = curl_exec($ch);

            if ($postcode_answer === false) {
                throw new Exception(curl_error($ch));
            }
            curl_close($ch);

            $address = array();

            $result = json_decode($postcode_answer);

            if (!$result) {
                throw new Exception('The answer is not a JSON string');
            }

            if ($result->success) {
                $address['straatnaam'] = $result->straatnaam;
                $address['woonplaats'] = $result->woonplaats;
            }

            return $address;
        } catch (Exception $e) {
            if ($this->_logIsEnabled) {
                Mage::logException($e);
            }

            return false;
        }
    }

    /**
     * Get remote IP address
     */
    protected function getRemoteIp() {
        $src = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($src as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
}
