<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin edit tabs
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('curriculum_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_traininglist')->__('Training Curriculum Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Edit_Tabs
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        $curriculum = $this->getCurriculum();
        $entity = Mage::getModel('eav/entity_type')
            ->load('bs_traininglist_curriculum', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        if($curriculum->getId()){
            $this->addTab('general_info', array(
                'label'     => Mage::helper('bs_traininglist')->__('General Information'),
                'content' => $this->getLayout()->createBlock(
                    'bs_traininglist/adminhtml_curriculum_edit_tab_info'
                )
                    ->toHtml()
            ));
        }

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('bs_traininglist')->__('Curriculum Information'),
                'content' => $this->getLayout()->createBlock(
                    'bs_traininglist/adminhtml_curriculum_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );

        $this->addTab(
            'subjects',
            array(
                'label' => Mage::helper('bs_subject')->__('Subjects'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/subject_subject_traininglist_curriculum/subjects',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );

        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('bs_traininglist')->__('Conducted Courses'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'categories',
            array(
                'label' => Mage::helper('bs_traininglist')->__('Curriculum Rating'),
                'url'   => $this->getUrl('*/*/categories', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'worksheets',
            array(
                'label' => Mage::helper('bs_traininglist')->__('Worksheets'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/worksheet_worksheet_traininglist_curriculum/worksheets',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );

        $this->addTab(
            'instructors',
            array(
                'label' => Mage::helper('bs_traininglist')->__('Instructors'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/instructor_instructor_traininglist_curriculum/instructors',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );


        $this->addTab(
            'curriculumdocs',
            array(
                'label' => Mage::helper('bs_traininglist')->__('Curriculum Documents'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/curriculumdoc_curriculumdoc_traininglist_curriculum/curriculumdocs',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve training curriculum entity
     *
     * @access public
     * @return BS_Traininglist_Model_Curriculum
     * @author Bui Phong
     */
    public function getCurriculum()
    {
        return Mage::registry('current_curriculum');
    }
}
