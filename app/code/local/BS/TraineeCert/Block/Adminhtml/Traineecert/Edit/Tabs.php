<?php
/**
 * BS_TraineeCert extension
 * 
 * @category       BS
 * @package        BS_TraineeCert
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Certificate admin edit tabs
 *
 * @category    BS
 * @package     BS_TraineeCert
 * @author Bui Phong
 */
class BS_TraineeCert_Block_Adminhtml_Traineecert_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('traineecert_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_traineecert')->__('Trainee Certificate'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_TraineeCert_Block_Adminhtml_Traineecert_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_traineecert',
            array(
                'label'   => Mage::helper('bs_traineecert')->__('Trainee Certificate'),
                'title'   => Mage::helper('bs_traineecert')->__('Trainee Certificate'),
                'content' => $this->getLayout()->createBlock(
                    'bs_traineecert/adminhtml_traineecert_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve trainee certificate entity
     *
     * @access public
     * @return BS_TraineeCert_Model_Traineecert
     * @author Bui Phong
     */
    public function getTraineecert()
    {
        return Mage::registry('current_traineecert');
    }
}
