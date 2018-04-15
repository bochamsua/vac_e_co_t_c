<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml trainee attribute edit page tabs
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Trainee_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('trainee_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_trainee')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return BS_Trainee_Adminhtml_Trainee_Attribute_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('bs_trainee')->__('Properties'),
                'title'     => Mage::helper('bs_trainee')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'bs_trainee/adminhtml_trainee_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('bs_trainee')->__('Manage Label / Options'),
                'title'     => Mage::helper('bs_trainee')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'bs_trainee/adminhtml_trainee_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
