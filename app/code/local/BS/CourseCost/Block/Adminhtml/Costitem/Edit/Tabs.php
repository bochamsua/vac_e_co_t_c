<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Group Items admin edit tabs
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Costitem_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('costitem_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_coursecost')->__('Manage Group Items'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Costitem_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_costitem',
            array(
                'label'   => Mage::helper('bs_coursecost')->__('Manage Group Items'),
                'title'   => Mage::helper('bs_coursecost')->__('Manage Group Items'),
                'content' => $this->getLayout()->createBlock(
                    'bs_coursecost/adminhtml_costitem_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve manage group items entity
     *
     * @access public
     * @return BS_CourseCost_Model_Costitem
     * @author Bui Phong
     */
    public function getCostitem()
    {
        return Mage::registry('current_costitem');
    }
}
