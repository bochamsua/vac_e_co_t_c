<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum attributes grid
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Attribute_Grid extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract
{
    /**
     * Prepare training curriculum attributes grid collection object
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Attribute_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_traininglist/curriculum_attribute_collection')
            ->addVisibleFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare curriculum attributes grid columns
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Attribute_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumnAfter(
            'is_global',
            array(
                'header'   => Mage::helper('bs_traininglist')->__('Scope'),
                'sortable' => true,
                'index'    => 'is_global',
                'type'     => 'options',
                'options'  => array(
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE   =>
                        Mage::helper('bs_traininglist')->__('Store View'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                        Mage::helper('bs_traininglist')->__('Website'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL  =>
                        Mage::helper('bs_traininglist')->__('Global'),
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
                'label'      => Mage::helper('bs_traininglist')->__('Change position'),
                'url'        => $this->getUrl('*/*/massPosition', array('_current'=>true)),
                'additional' => array(
                    'position' => array(
                        'name'   => 'position',
                        'type'   => 'text',

                        'label'  => Mage::helper('bs_traininglist')->__('Position'),

                    )
                )
            )
        );

        return $this;
    }

}
