<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin edit tabs
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Exam_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('exam_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_docwise')->__('Exam'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_exam',
            array(
                'label'   => Mage::helper('bs_docwise')->__('Exam'),
                'title'   => Mage::helper('bs_docwise')->__('Exam'),
                'content' => $this->getLayout()->createBlock(
                    'bs_docwise/adminhtml_exam_edit_tab_form'
                )
                ->toHtml(),
            )
        );

        if($this->getExam()->getId()){
            $this->addTab(
                'trainees',
                array(
                    'label' => Mage::helper('bs_docwise')->__('Trainees & Scores'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/docwise_exam/trainees',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );

            $this->addTab(
                'docwisements',
                array(
                    'label' => Mage::helper('bs_docwise')->__('Documents'),
                    'url'   => $this->getUrl('*/*/docwisements', array('_current' => true)),
                    'class' => 'ajax'
                )
            );

            $this->addTab(
                'filefolders',
                array(
                    'label' => Mage::helper('bs_docwise')->__('File folders'),
                    'url'   => $this->getUrl('*/*/filefolders', array('_current' => true)),
                    'class' => 'ajax'
                )
            );

        }

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve exam entity
     *
     * @access public
     * @return BS_Docwise_Model_Exam
     * @author Bui Phong
     */
    public function getExam()
    {
        return Mage::registry('current_exam');
    }
}
