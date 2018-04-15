<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * QA Car admin edit tabs
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Block_Adminhtml_Qacar_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('qacar_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_car')->__('QA Car'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Qacar_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_qacar',
            array(
                'label'   => Mage::helper('bs_car')->__('QA Car'),
                'title'   => Mage::helper('bs_car')->__('QA Car'),
                'content' => $this->getLayout()->createBlock(
                    'bs_car/adminhtml_qacar_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve qa car entity
     *
     * @access public
     * @return BS_Car_Model_Qacar
     * @author Bui Phong
     */
    public function getQacar()
    {
        return Mage::registry('current_qacar');
    }
}
