<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Questionnaire admin edit tabs
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
class BS_Questionnaire_Block_Adminhtml_Questionnaire_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('questionnaire_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_questionnaire')->__('Questionnaire'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Questionnaire_Block_Adminhtml_Questionnaire_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_questionnaire',
            array(
                'label'   => Mage::helper('bs_questionnaire')->__('Questionnaire'),
                'title'   => Mage::helper('bs_questionnaire')->__('Questionnaire'),
                'content' => $this->getLayout()->createBlock(
                    'bs_questionnaire/adminhtml_questionnaire_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve questionnaire entity
     *
     * @access public
     * @return BS_Questionnaire_Model_Questionnaire
     * @author Bui Phong
     */
    public function getQuestionnaire()
    {
        return Mage::registry('current_questionnaire');
    }
}
