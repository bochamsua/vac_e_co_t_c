<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Other Info admin edit tabs
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Block_Adminhtml_Otherinfo_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('otherinfo_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_instructorinfo')->__('Other Info'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Otherinfo_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_otherinfo',
            array(
                'label'   => Mage::helper('bs_instructorinfo')->__('Other Info'),
                'title'   => Mage::helper('bs_instructorinfo')->__('Other Info'),
                'content' => $this->getLayout()->createBlock(
                    'bs_instructorinfo/adminhtml_otherinfo_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve other info entity
     *
     * @access public
     * @return BS_InstructorInfo_Model_Otherinfo
     * @author Bui Phong
     */
    public function getOtherinfo()
    {
        return Mage::registry('current_otherinfo');
    }
}
