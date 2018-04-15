<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Family attributes grid
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Block_Adminhtml_Family_Attribute_Grid extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract
{
    /**
     * Prepare family attributes grid collection object
     *
     * @access protected
     * @return BS_Tc_Block_Adminhtml_Family_Attribute_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_tc/family_attribute_collection')
            ->addVisibleFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare family attributes grid columns
     *
     * @access protected
     * @return BS_Tc_Block_Adminhtml_Family_Attribute_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumnAfter(
            'is_global',
            array(
                'header'   => Mage::helper('bs_tc')->__('Scope'),
                'sortable' => true,
                'index'    => 'is_global',
                'type'     => 'options',
                'options'  => array(
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE   =>
                        Mage::helper('bs_tc')->__('Store View'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                        Mage::helper('bs_tc')->__('Website'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL  =>
                        Mage::helper('bs_tc')->__('Global'),
                ),
                'align' => 'center',
            ),
            'is_user_defined'
        );
        $this->addColumnAfter('position', array(
                    'header'=>Mage::helper('eav')->__('Position'),
                    'sortable'=>true,
                    'index'=>'position'
                ),'is_global');
        return $this;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('attributes');

        $this->getMassactionBlock()->addItem(
            'position',
            array(
                'label'      => Mage::helper('bs_tc')->__('Change position'),
                'url'        => $this->getUrl('*/*/massPosition', array('_current'=>true)),
                'additional' => array(
                    'position' => array(
                        'name'   => 'position',
                        'type'   => 'text',

                        'label'  => Mage::helper('bs_tc')->__('Position'),

                    )
                )
            )
        );

        return $this;
    }
}
