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

class TIG_PostCode_OnepageController extends Mage_Core_Controller_Front_Action
{
    /**
     * Get Street Name and City Name by Postcode and House Number
     * and then send this data in JSON
     */
    public function nlAddressAction()
    {
        $housenumber = (string) $this->getRequest()->getParam('housenumber');
        $postcode = (string) $this->getRequest()->getParam('postcode');
        $address = str_replace(' ', '', $postcode) . trim($housenumber);
        $response = array(
            'query' => $address,
            'status' => 'error'
        );
        if ($address) {
            $out = Mage::getModel('tig_postcode/address')->search($postcode, $housenumber);
            Mage::log($out, null, 'TIG_DEBUG.log', true);
            if ($out) {
                $response['status'] = 'success';
                $response['data'] = (array)$out;
            }
        }
        $this->getResponse()
            ->setHeader('Content-type', 'application/json', true)
            ->setBody(Zend_Json::encode($response))
            ->sendResponse();
        exit;
    }

}