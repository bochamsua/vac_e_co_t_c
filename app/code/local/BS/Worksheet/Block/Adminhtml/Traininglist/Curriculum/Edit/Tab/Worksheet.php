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
 * Worksheet tab on curriculum edit form
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Worksheet extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('worksheet_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getCurriculum()->getId()) {
            $this->setDefaultFilter(array('in_worksheets'=>1));
        }
    }

    /**
     * prepare the worksheet collection
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Worksheet
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_worksheet/worksheet_collection');
        if ($this->getCurriculum()->getId()) {
            $constraint = 'related.curriculum_id='.$this->getCurriculum()->getId();
        } else {
            $constraint = 'related.curriculum_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_worksheet/worksheet_curriculum')),
            'related.worksheet_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Worksheet
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
     * @return BS_Worksheet_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Worksheet
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_worksheets',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_worksheets',
                'values'=> $this->_getSelectedWorksheets(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'ws_name',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Worksheet Name'),
                'align'  => 'left',
                'index'  => 'ws_name',
                'renderer' => 'bs_worksheet/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/worksheet_worksheet/edit',
            )
        );
        $this->addColumn(
            'ws_code',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Worksheet Code'),
                'align'  => 'left',
                'index'  => 'ws_code',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_worksheet')->__('Position'),
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
     * Retrieve selected worksheets
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedWorksheets()
    {
        $worksheets = $this->getCurriculumWorksheets();
        if (!is_array($worksheets)) {
            $worksheets = array_keys($this->getSelectedWorksheets());
        }
        return $worksheets;
    }

    /**
     * Retrieve selected worksheets
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedWorksheets()
    {
        $worksheets = array();
        //used helper here in order not to override the curriculum model
        $selected = Mage::helper('bs_worksheet/curriculum')->getSelectedWorksheets(Mage::registry('current_curriculum'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $worksheet) {
            $worksheets[$worksheet->getId()] = array('position' => $worksheet->getPosition());
        }
        return $worksheets;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Worksheet_Model_Worksheet
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
            '*/*/worksheetsGrid',
            array(
                'id'=>$this->getCurriculum()->getId()
            )
        );
    }

    /**
     * get the current curriculum
     *
     * @access public
     * @return BS_Traininglist_Model_Curriculum
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
     * @return BS_Worksheet_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Worksheet
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_worksheets') {
            $worksheetIds = $this->_getSelectedWorksheets();
            if (empty($worksheetIds)) {
                $worksheetIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$worksheetIds));
            } else {
                if ($worksheetIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$worksheetIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
