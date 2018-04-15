<?php
/**
 * BS_Assessment extension
 * 
 * @category       BS
 * @package        BS_Assessment
 * @copyright      Copyright (c) 2015
 */
/**
 * Assessment admin edit tabs
 *
 * @category    BS
 * @package     BS_Assessment
 * @author Bui Phong
 */
class BS_Assessment_Block_Adminhtml_Assessment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('assessment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_assessment')->__('Assessment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Assessment_Block_Adminhtml_Assessment_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_assessment',
            array(
                'label'   => Mage::helper('bs_assessment')->__('Assessment'),
                'title'   => Mage::helper('bs_assessment')->__('Assessment'),
                'content' => $this->getLayout()->createBlock(
                    'bs_assessment/adminhtml_assessment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve assessment entity
     *
     * @access public
     * @return BS_Assessment_Model_Assessment
     * @author Bui Phong
     */
    public function getAssessment()
    {
        return Mage::registry('current_assessment');
    }
}
