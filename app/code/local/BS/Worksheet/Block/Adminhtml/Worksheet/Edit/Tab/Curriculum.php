<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet - curriculum relation edit block
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tab_Curriculum extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('curriculum_grid');
        //$this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getWorksheet()->getId()) {
            $this->setDefaultFilter(array('in_curriculums'=>1));
        }
    }

    /**
     * prepare the curriculum collection
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {

        $collection = Mage::getResourceModel('bs_traininglist/curriculum_collection');
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute('c_name', 'bs_traininglist_curriculum/c_name', 'entity_id', null, 'left', $adminStore);
        $collection->joinAttribute('c_code', 'bs_traininglist_curriculum/c_code', 'entity_id', null, 'left', $adminStore);

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tab_Curriculum
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
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_curriculums',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_curriculums',
                'values'=> $this->_getSelectedCurriculums(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'c_name',
            array(
                'header'    => Mage::helper('bs_traininglist')->__('Curriculum Name'),
                'align'     => 'left',
                'index'     => 'c_name',
                'renderer'  => 'bs_worksheet/adminhtml_helper_column_renderer_relation',
                'params'    => array(
                    'id'    => 'getId'
                ),
                'base_link' => 'adminhtml/traininglist_curriculum/edit',
            )
        );
        $this->addColumn(
            'c_code',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Curriculum Code'),
                'align'  => 'left',
                'index'  => 'c_code',
            )
        );
        /*
        $this->addColumn(
            'price',
            array(
                'header'        => Mage::helper('bs_traininglist')->__('Price'),
                'type'          => 'currency',
                'width'         => '1',
                'currency_code' => (string)Mage::getStoreConfig(
                    Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE
                ),
                'index'         => 'price'
            )
        );*/
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_traininglist')->__('Position'),
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
     * Retrieve selected curriculums
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedCurriculums()
    {
        $curriculums = $this->getWorksheetCurriculums();
        if (!is_array($curriculums)) {
            $curriculums = array_keys($this->getSelectedCurriculums());
        }
        return $curriculums;
    }

    /**
     * Retrieve selected curriculums
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedCurriculums()
    {
        $curriculums = array();
        $selected = Mage::registry('current_worksheet')->getSelectedCurriculums();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $curriculum) {
            $curriculums[$curriculum->getId()] = array('position' => $curriculum->getPosition());
        }
        return $curriculums;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Worksheet_Model_Curriculum
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
            '*/*/curriculumsGrid',
            array(
                'id' => $this->getWorksheet()->getId()
            )
        );
    }

    /**
     * get the current worksheet
     *
     * @access public
     * @return BS_Worksheet_Model_Worksheet
     * @author Bui Phong
     */
    public function getWorksheet()
    {
        return Mage::registry('current_worksheet');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in curriculum flag
        if ($column->getId() == 'in_curriculums') {
            $curriculumIds = $this->_getSelectedCurriculums();
            if (empty($curriculumIds)) {
                $curriculumIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $curriculumIds));
            } else {
                if ($curriculumIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $curriculumIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
