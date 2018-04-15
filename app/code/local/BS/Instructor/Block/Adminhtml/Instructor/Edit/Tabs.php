<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin edit tabs
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('instructor_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_instructor')->__('Instructor Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructor_Edit_Tabs
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        $instructor = $this->getInstructor();
        $entity = Mage::getModel('eav/entity_type')
            ->load('bs_instructor_instructor', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('bs_instructor')->__('Instructor Information'),
                'content' => $this->getLayout()->createBlock(
                    'bs_instructor/adminhtml_instructor_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );
        $this->addTab(
            'approved_info',
            array(
                'label' => Mage::helper('bs_instructor')->__('Approved Function Record'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/instructorinfo_instructor/infos',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );

        $this->addTab(
            'approved_otherinfo',
            array(
                'label' => Mage::helper('bs_instructor')->__('Other Record'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/instructorinfo_instructor/otherinfos',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('bs_instructor')->__('Instructed Courses'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
//        $this->addTab(
//            'categories',
//            array(
//                'label' => Mage::helper('bs_instructor')->__('Rating'),
//                'url'   => $this->getUrl('*/*/categories', array('_current' => true)),
//                'class' => 'ajax'
//            )
//        );

        $this->addTab(
            'curriculums',
            array(
                'label' => Mage::helper('bs_instructor')->__('Approved Courses'),
                'url'   => $this->getUrl('*/*/curriculums', array('_current' => true)),
                'class' => 'ajax'
            )
        );

        $this->addTab(
            'instructordocs',
            array(
                'label' => Mage::helper('bs_material')->__('Instructor Documents'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/material_instructordoc_instructor_instructor/instructordocs',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve instructor entity
     *
     * @access public
     * @return BS_Instructor_Model_Instructor
     * @author Bui Phong
     */
    public function getInstructor()
    {
        return Mage::registry('current_instructor');
    }
}
