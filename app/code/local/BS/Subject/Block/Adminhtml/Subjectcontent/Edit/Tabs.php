<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Content admin edit tabs
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Subjectcontent_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('subjectcontent_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_subject')->__('Subject Content'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_subjectcontent',
            array(
                'label'   => Mage::helper('bs_subject')->__('Subject Content'),
                'title'   => Mage::helper('bs_subject')->__('Subject Content'),
                'content' => $this->getLayout()->createBlock(
                    'bs_subject/adminhtml_subjectcontent_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve subject content entity
     *
     * @access public
     * @return BS_Subject_Model_Subjectcontent
     * @author Bui Phong
     */
    public function getSubjectcontent()
    {
        return Mage::registry('current_subjectcontent');
    }
}
