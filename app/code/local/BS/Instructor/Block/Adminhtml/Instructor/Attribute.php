<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin attribute block
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructor_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_instructor_attribute';
        $this->_blockGroup = 'bs_instructor';
        $this->_headerText = Mage::helper('bs_instructor')->__('Manage Instructor Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('bs_instructor')->__('Add New Instructor Attribute')
        );
    }
}
