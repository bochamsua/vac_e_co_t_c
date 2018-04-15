<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml instructor attribute edit page tabs
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructor_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('instructor_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_instructor')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return BS_Instructor_Adminhtml_Instructor_Attribute_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('bs_instructor')->__('Properties'),
                'title'     => Mage::helper('bs_instructor')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'bs_instructor/adminhtml_instructor_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('bs_instructor')->__('Manage Label / Options'),
                'title'     => Mage::helper('bs_instructor')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'bs_instructor/adminhtml_instructor_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
