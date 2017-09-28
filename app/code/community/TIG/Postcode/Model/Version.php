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
 * TIG PostCode Version model
 *
 * @package    TIG_PostCode
 * @copyright  Copyright (c) 2011 Total Internet Group (http://www.totalinternetgroup.nl)
 * @author     Total Internet Group (http://www.totalinternetgroup.nl)
 */
class TIG_PostCode_Model_Version
{

    /**
     * Returned the version of the module
     *
     * @param string $moduleName
     */
    public function getModule($moduleName) {
        $configPath = Mage::getModuleDir('etc', $moduleName) . DS . "config.xml";
        $xmlConfig = new Varien_Simplexml_Config($configPath);

        return (string)$xmlConfig->getNode('modules/' . $moduleName . '/version');
    }

}
