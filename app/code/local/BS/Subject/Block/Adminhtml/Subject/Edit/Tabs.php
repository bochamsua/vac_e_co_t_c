<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin edit tabs
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Subject_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('subject_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_subject')->__('Subject'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subject_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_subject',
            array(
                'label'   => Mage::helper('bs_subject')->__('Subject Info'),
                'title'   => Mage::helper('bs_subject')->__('Subject Info'),
                'content' => $this->getLayout()->createBlock(
                    'bs_subject/adminhtml_subject_edit_tab_form'
                )
                ->toHtml(),
            )
        );

        if($this->getSubject()->getId()){
            $this->addTab(
                'subcontent',
                array(
                    'label' => Mage::helper('catalog')->__('Subject Content'),
                    'url'   => $this->getUrl('adminhtml/subject_subject/subjectcontents', array('_current' => true)),
                    'class' => 'ajax',
                )
            );
        }

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve subject entity
     *
     * @access public
     * @return BS_Subject_Model_Subject
     * @author Bui Phong
     */
    public function getSubject()
    {
        return Mage::registry('current_subject');
    }
}
