<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Migration admin edit tabs
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Block_Adminhtml_Migration_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('migration_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_tools')->__('Migration'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Tools_Block_Adminhtml_Migration_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_migration',
            array(
                'label'   => Mage::helper('bs_tools')->__('Migration'),
                'title'   => Mage::helper('bs_tools')->__('Migration'),
                'content' => $this->getLayout()->createBlock(
                    'bs_tools/adminhtml_migration_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve migration entity
     *
     * @access public
     * @return BS_Tools_Model_Migration
     * @author Bui Phong
     */
    public function getMigration()
    {
        return Mage::registry('current_migration');
    }
}
