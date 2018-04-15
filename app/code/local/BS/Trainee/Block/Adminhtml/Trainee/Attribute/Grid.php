<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Trainee attributes grid
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Trainee_Attribute_Grid extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract
{
    /**
     * Prepare trainee attributes grid collection object
     *
     * @access protected
     * @return BS_Trainee_Block_Adminhtml_Trainee_Attribute_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_trainee/trainee_attribute_collection')
            ->addVisibleFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare trainee attributes grid columns
     *
     * @access protected
     * @return BS_Trainee_Block_Adminhtml_Trainee_Attribute_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumnAfter(
            'is_global',
            array(
                'header'   => Mage::helper('bs_trainee')->__('Scope'),
                'sortable' => true,
                'index'    => 'is_global',
                'type'     => 'options',
                'options'  => array(
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE   =>
                        Mage::helper('bs_trainee')->__('Store View'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                        Mage::helper('bs_trainee')->__('Website'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL  =>
                        Mage::helper('bs_trainee')->__('Global'),
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
                'label'      => Mage::helper('bs_trainee')->__('Change position'),
                'url'        => $this->getUrl('*/*/massPosition', array('_current'=>true)),
                'additional' => array(
                    'position' => array(
                        'name'   => 'position',
                        'type'   => 'text',

                        'label'  => Mage::helper('bs_trainee')->__('Position'),

                    )
                )
            )
        );

        return $this;
    }
}
