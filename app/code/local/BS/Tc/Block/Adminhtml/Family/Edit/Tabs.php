<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Family admin edit tabs
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Block_Adminhtml_Family_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('family_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_tc')->__('Family Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Tc_Block_Adminhtml_Family_Edit_Tabs
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        $family = $this->getFamily();
        $entity = Mage::getModel('eav/entity_type')
            ->load('bs_tc_family', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('bs_tc')->__('Family Information'),
                'content' => $this->getLayout()->createBlock(
                    'bs_tc/adminhtml_family_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve family entity
     *
     * @access public
     * @return BS_Tc_Model_Family
     * @author Bui Phong
     */
    public function getFamily()
    {
        return Mage::registry('current_family');
    }
}
