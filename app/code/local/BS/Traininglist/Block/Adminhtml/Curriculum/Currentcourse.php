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
class BS_Traininglist_Block_Adminhtml_Curriculum_Currentcourse extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_curriculum_currentcourse';
        $this->_blockGroup = 'bs_traininglist';
        $this->_headerText = Mage::helper('bs_traininglist')->__('Current Course');
        parent::__construct();
        $this->_removeButton('add');
        $isAllowedAdd = Mage::getSingleton('admin/session')->isAllowed('catalog/products/new');
        if($isAllowedAdd){
            $this->_addButton('add_new', array(
                'label'   => Mage::helper('catalog')->__('Add Course'),
                'onclick' => "setLocation('{$this->getUrl('*/catalog_product/new', array('set'=>'4', 'type'=>'simple', 'from'=>'currentcourse'))}')",//set/4/type/simple/
                'class'   => 'add'
            ));
        }

    }
}
