<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml training curriculum attribute edit page tabs
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('curriculum_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_traininglist')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return BS_Traininglist_Adminhtml_Curriculum_Attribute_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('bs_traininglist')->__('Properties'),
                'title'     => Mage::helper('bs_traininglist')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'bs_traininglist/adminhtml_curriculum_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('bs_traininglist')->__('Manage Label / Options'),
                'title'     => Mage::helper('bs_traininglist')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'bs_traininglist/adminhtml_curriculum_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
