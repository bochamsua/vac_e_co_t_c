<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Employee admin attribute block
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Block_Adminhtml_Employee_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_employee_attribute';
        $this->_blockGroup = 'bs_tc';
        $this->_headerText = Mage::helper('bs_tc')->__('Manage Employee Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('bs_tc')->__('Add New Employee Attribute')
        );
    }
}
