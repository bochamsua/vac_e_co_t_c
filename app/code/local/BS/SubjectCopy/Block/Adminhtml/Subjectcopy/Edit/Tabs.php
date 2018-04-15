<?php
/**
 * BS_SubjectCopy extension
 * 
 * @category       BS
 * @package        BS_SubjectCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Copy admin edit tabs
 *
 * @category    BS
 * @package     BS_SubjectCopy
 * @author Bui Phong
 */
class BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('subjectcopy_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_subjectcopy')->__('Subject Copy'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_subjectcopy',
            array(
                'label'   => Mage::helper('bs_subjectcopy')->__('Subject Copy'),
                'title'   => Mage::helper('bs_subjectcopy')->__('Subject Copy'),
                'content' => $this->getLayout()->createBlock(
                    'bs_subjectcopy/adminhtml_subjectcopy_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve subject copy entity
     *
     * @access public
     * @return BS_SubjectCopy_Model_Subjectcopy
     * @author Bui Phong
     */
    public function getSubjectcopy()
    {
        return Mage::registry('current_subjectcopy');
    }
}
