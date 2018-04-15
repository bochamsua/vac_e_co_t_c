<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor tab on curriculum edit form
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Instructor extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('instructor_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getCurriculum()->getId()) {
            $this->setDefaultFilter(array('in_instructors'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_instructor_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Import Instructor'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/importinstructor_importinstructor/new', array('_current'=>false, 'curriculum_id'=>$this->getCurriculum()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('copy_instructor_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Copy Instructor'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/instructorcopy_instructorcopy/new', array('_current'=>false, 'c_to'=>$this->getCurriculum()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );
        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_instructor_button');
            $html.= $this->getChildHtml('copy_instructor_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }
    /**
     * prepare the instructor collection
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Catalog_Curriculum_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_instructor/instructor_collection')->addAttributeToSelect('iname')->addAttributeToSelect('ivaecoid');

        if ($this->getCurriculum()->getId()) {
            $constraint = '{{table}}.curriculum_id='.$this->getCurriculum()->getId();
        } else {
            $constraint = '{{table}}.curriculum_id=0';
        }
        $collection->joinField(
            'position',
            'bs_instructor/instructor_curriculum',
            'position',
            'instructor_id=entity_id',
            $constraint,
            'left'
        );

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Catalog_Curriculum_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Catalog_Curriculum_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_instructors',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_instructors',
                'values'=> $this->_getSelectedInstructors(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'iname',
            array(
                'header' => Mage::helper('bs_instructor')->__('Instructor Name'),
                'align'  => 'left',
                'index'  => 'iname',
                'renderer' => 'bs_instructor/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/instructor_instructor/edit',
            )
        );


        $this->addColumn(
            'ivaecoid',
            array(
                'header' => Mage::helper('bs_instructor')->__('VAECO ID'),
                'align'  => 'left',
                'index'  => 'ivaecoid',

            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_instructor')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected instructors
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedInstructors()
    {
        $instructors = $this->getCurriculumInstructors();
        if (!is_array($instructors)) {
            $instructors = array_keys($this->getSelectedInstructors());
        }
        return $instructors;
    }

    /**
     * Retrieve selected instructors
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedInstructors()
    {
        $instructors = array();
        //used helper here in order not to override the curriculum model
        $selected = Mage::helper('bs_instructor/curriculum')->getSelectedInstructors(Mage::registry('current_curriculum'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $instructor) {
            $instructors[$instructor->getId()] = array('position' => $instructor->getPosition());
        }
        return $instructors;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Instructor_Model_Instructor
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/instructorsGrid',
            array(
                'id'=>$this->getCurriculum()->getId()
            )
        );
    }

    /**
     * get the current curriculum
     *
     * @access public
     * @return Mage_Catalog_Model_Curriculum
     * @author Bui Phong
     */
    public function getCurriculum()
    {
        return Mage::registry('current_curriculum');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Instructor_Block_Adminhtml_Catalog_Curriculum_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_instructors') {
            $instructorIds = $this->_getSelectedInstructors();
            if (empty($instructorIds)) {
                $instructorIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$instructorIds));
            } else {
                if ($instructorIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$instructorIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
