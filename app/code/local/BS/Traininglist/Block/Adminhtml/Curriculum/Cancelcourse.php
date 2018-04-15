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
class BS_Traininglist_Block_Adminhtml_Curriculum_Cancelcourse extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_curriculum_cancelcourse';
        $this->_blockGroup = 'bs_traininglist';
        $this->_headerText = Mage::helper('bs_traininglist')->__('Cancelled Course');
        parent::__construct();
        $this->_removeButton('add');
//        $this->_addButton('add_new', array(
//            'label'   => Mage::helper('catalog')->__('Add Course'),
//            'onclick' => "setLocation('{$this->getUrl('*/catalog_product/new', array('set'=>'4', 'type'=>'simple', 'from'=>'completecourse'))}')",//set/4/type/simple/
//            'class'   => 'add'
//        ));
    }
}
