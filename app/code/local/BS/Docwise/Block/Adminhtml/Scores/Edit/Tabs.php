<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Score (OLD) admin edit tabs
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Scores_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('scores_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_docwise')->__('Score (OLD)'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Scores_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_scores',
            array(
                'label'   => Mage::helper('bs_docwise')->__('Score (OLD)'),
                'title'   => Mage::helper('bs_docwise')->__('Score (OLD)'),
                'content' => $this->getLayout()->createBlock(
                    'bs_docwise/adminhtml_scores_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve score (old) entity
     *
     * @access public
     * @return BS_Docwise_Model_Scores
     * @author Bui Phong
     */
    public function getScores()
    {
        return Mage::registry('current_scores');
    }
}
