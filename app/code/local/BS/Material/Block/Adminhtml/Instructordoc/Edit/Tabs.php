<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document admin edit tabs
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Block_Adminhtml_Instructordoc_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('instructordoc_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_material')->__('Instructor Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Material_Block_Adminhtml_Instructordoc_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_instructordoc',
            array(
                'label'   => Mage::helper('bs_material')->__('Document Info'),
                'title'   => Mage::helper('bs_material')->__('Document Info'),
                'content' => $this->getLayout()->createBlock(
                    'bs_material/adminhtml_instructordoc_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'instructors',
            array(
                'label' => Mage::helper('bs_material')->__('Related instructors'),
                'url'   => $this->getUrl('*/*/instructors', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve instructor doc entity
     *
     * @access public
     * @return BS_Material_Model_Instructordoc
     * @author Bui Phong
     */
    public function getInstructordoc()
    {
        return Mage::registry('current_instructordoc');
    }
}
