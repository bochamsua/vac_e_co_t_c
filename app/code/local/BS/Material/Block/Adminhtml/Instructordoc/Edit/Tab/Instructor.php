<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document - instructor relation edit block
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Block_Adminhtml_Instructordoc_Edit_Tab_Instructor extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access protected
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('instructor_grid');
        //$this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getInstructordoc()->getId()) {
            $this->setDefaultFilter(array('in_instructors'=>1));
        }
    }

    /**
     * prepare the instructor collection
     *
     * @access protected
     * @return BS_Material_Block_Adminhtml_Instructordoc_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_instructor/instructor_collection');

        $collection = Mage::getModel('bs_instructor/instructor')
            ->getCollection()
            ->addAttributeToSelect('status');

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute(
            'iname',
            'bs_instructor_instructor/iname',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'ivaecoid',
            'bs_instructor_instructor/ivaecoid',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Material_Block_Adminhtml_Instructordoc_Edit_Tab_Instructor
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
     * @return BS_Material_Block_Adminhtml_Instructordoc_Edit_Tab_Instructor
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
                'header'    => Mage::helper('bs_instructor')->__('Name'),
                'align'     => 'left',
                'index'     => 'iname',
                'renderer'  => 'bs_material/adminhtml_helper_column_renderer_relation',
                'params'    => array(
                    'id'    => 'getId'
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
        $instructors = $this->getInstructordocInstructors();
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
        $selected = Mage::registry('current_instructordoc')->getSelectedInstructors();
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
     * @param BS_Material_Model_Instructor
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
                'id' => $this->getInstructordoc()->getId()
            )
        );
    }

    /**
     * get the current instructor doc
     *
     * @access public
     * @return BS_Material_Model_Instructordoc
     * @author Bui Phong
     */
    public function getInstructordoc()
    {
        return Mage::registry('current_instructordoc');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Material_Block_Adminhtml_Instructordoc_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in instructor flag
        if ($column->getId() == 'in_instructors') {
            $instructorIds = $this->_getSelectedInstructors();
            if (empty($instructorIds)) {
                $instructorIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $instructorIds));
            } else {
                if ($instructorIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $instructorIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
