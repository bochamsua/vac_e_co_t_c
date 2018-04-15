<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor attributes grid
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructor_Attribute_Grid extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract
{
    /**
     * Prepare instructor attributes grid collection object
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructor_Attribute_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_instructor/instructor_attribute_collection')
            ->addVisibleFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare instructor attributes grid columns
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructor_Attribute_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumnAfter(
            'is_global',
            array(
                'header'   => Mage::helper('bs_instructor')->__('Scope'),
                'sortable' => true,
                'index'    => 'is_global',
                'type'     => 'options',
                'options'  => array(
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE   =>
                        Mage::helper('bs_instructor')->__('Store View'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                        Mage::helper('bs_instructor')->__('Website'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL  =>
                        Mage::helper('bs_instructor')->__('Global'),
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
                'label'      => Mage::helper('bs_instructor')->__('Change position'),
                'url'        => $this->getUrl('*/*/massPosition', array('_current'=>true)),
                'additional' => array(
                    'position' => array(
                        'name'   => 'position',
                        'type'   => 'text',

                        'label'  => Mage::helper('bs_instructor')->__('Position'),

                    )
                )
            )
        );

        return $this;
    }
}
