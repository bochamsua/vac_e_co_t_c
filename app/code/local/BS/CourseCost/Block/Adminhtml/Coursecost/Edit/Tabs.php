<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Course Cost admin edit tabs
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Coursecost_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('coursecost_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_coursecost')->__('Course Cost'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_coursecost',
            array(
                'label'   => Mage::helper('bs_coursecost')->__('Course Cost'),
                'title'   => Mage::helper('bs_coursecost')->__('Course Cost'),
                'content' => $this->getLayout()->createBlock(
                    'bs_coursecost/adminhtml_coursecost_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve course cost entity
     *
     * @access public
     * @return BS_CourseCost_Model_Coursecost
     * @author Bui Phong
     */
    public function getCoursecost()
    {
        return Mage::registry('current_coursecost');
    }
}
