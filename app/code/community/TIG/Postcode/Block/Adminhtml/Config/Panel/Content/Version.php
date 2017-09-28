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

class TIG_PostCode_Block_Adminhtml_Config_Panel_Content_Version extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

    public function render(Varien_Data_Form_Element_Abstract $element) {

        $configPath = Mage::getModuleDir('etc', 'TIG_Postcode') . DS . "config.xml";
        $version = $this->__('Not set');

        try {
            $xmlConfig = new Varien_Simplexml_Config($configPath);
            $version = $xmlConfig->getNode('modules/TIG_Postcode/version');
        }
        catch (Exception $eException) {
            $version = $this->__('Unable to read ') . $configPath;
        }

        return '<tr class="system-fieldset-sub-head" id="row_"' . htmlentities($element->getHtmlId()) . '">'.
                    '<td class="label">' . htmlentities($this->__('Version')) . '</td>'.
                    '<td class="value">' . htmlentities($version) . '</td>'.
               '</tr>';
    }

}
