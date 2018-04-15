<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Cost Group admin edit tabs
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Costgroup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('costgroup_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_coursecost')->__('Manage Cost Group'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Costgroup_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_costgroup',
            array(
                'label'   => Mage::helper('bs_coursecost')->__('Manage Cost Group'),
                'title'   => Mage::helper('bs_coursecost')->__('Manage Cost Group'),
                'content' => $this->getLayout()->createBlock(
                    'bs_coursecost/adminhtml_costgroup_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve manage cost group entity
     *
     * @access public
     * @return BS_CourseCost_Model_Costgroup
     * @author Bui Phong
     */
    public function getCostgroup()
    {
        return Mage::registry('current_costgroup');
    }
}
