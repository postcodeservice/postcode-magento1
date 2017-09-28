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
 * The TIG Postcode helper
 *
 * @category   TIG
 * @package    TIG_Postcode
 */
class TIG_Postcode_Helper_Data extends Mage_Core_Helper_Data
{
    private $_OSVersion = 0;

    /**
     * Returned the version of the Idev_OneStepCheckout module
     *
     */
    public function getOSVersion() {
        if (!$this->_OSVersion) {
            $version = Mage::getModel('tig_postcode/version')->getModule('Idev_OneStepCheckout');
            list($version,) = explode('.', $version, 2);
            $this->_OSVersion = $version <= 3 ? 3 : 4;
        }

        return $this->_OSVersion;
    }

    public function getOSBilligTemplate() {
        return 'tig/postcode/onestepcheckout/v' . $this->getOSVersion() . '/billing_fields.phtml';
    }

    public function getOSShippingTemplate() {
        return 'tig/postcode/onestepcheckout/v' . $this->getOSVersion() . '/shipping_fields.phtml';
    }

}
