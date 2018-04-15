<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin attribute block
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_curriculum_attribute';
        $this->_blockGroup = 'bs_traininglist';
        $this->_headerText = Mage::helper('bs_traininglist')->__('Manage Training Curriculum Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('bs_traininglist')->__('Add New Training Curriculum Attribute')
        );
    }
}
