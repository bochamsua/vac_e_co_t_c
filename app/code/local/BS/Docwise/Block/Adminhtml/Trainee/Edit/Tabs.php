<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee admin edit tabs
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Trainee_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('trainee_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_docwise')->__('Trainee'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Trainee_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_trainee',
            array(
                'label'   => Mage::helper('bs_docwise')->__('Trainee'),
                'title'   => Mage::helper('bs_docwise')->__('Trainee'),
                'content' => $this->getLayout()->createBlock(
                    'bs_docwise/adminhtml_trainee_edit_tab_form'
                )
                ->toHtml(),
            )
        );

        if($this->getTrainee()->getId()){
            $this->addTab(
                'trainees',
                array(
                    'label' => Mage::helper('bs_docwise')->__('Exams'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/docwise_trainee/exams',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve trainee entity
     *
     * @access public
     * @return BS_Docwise_Model_Trainee
     * @author Bui Phong
     */
    public function getTrainee()
    {
        return Mage::registry('current_trainee');
    }
}
