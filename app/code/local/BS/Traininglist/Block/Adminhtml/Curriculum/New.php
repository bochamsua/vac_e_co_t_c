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
class BS_Traininglist_Block_Adminhtml_Curriculum_New extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_curriculum_new';
        $this->_blockGroup = 'bs_traininglist';
        $this->_headerText = Mage::helper('bs_traininglist')->__('New Curriculum Approval');
        parent::__construct();
        $this->_removeButton('add');

        $this->_addButton('add_new', array(
            'label'   => Mage::helper('catalog')->__('Add New Curriculum'),
            'onclick' => "setLocation('{$this->getUrl('*/traininglist_curriculum/new', array('backto'=>'new'))}')",//set/4/type/simple/
            'class'   => 'add'
        ));
    }
}
