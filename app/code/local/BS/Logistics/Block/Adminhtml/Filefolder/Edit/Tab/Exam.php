<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder - product relation edit block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tab_Exam extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('exam_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getFilefolder()->getId()) {
            $this->setDefaultFilter(array('in_exams'=>1));
        }
    }

    /**
     * prepare the product collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_docwise/exam_collection');

        if ($this->getFilefolder()->getId()) {
            $constraint = 'related.filefolder_id='.$this->getFilefolder()->getId();
        } else {
            $constraint = 'related.filefolder_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_docwise/exam_filefolder')),
            'related.exam_id = main_table.entity_id AND '.$constraint,
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
     * @return BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tab_Product
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
     * @return BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_exams',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_exams',
                'values'=> $this->_getSelectedExams(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        
        $this->addColumn(
            'exam_code',
            array(
                'header'    => Mage::helper('bs_docwise')->__('Code'),
                'align'     => 'left',
                'index'     => 'exam_code',
            )
        );


        $this->addColumn(
            'exam_date',
            array(
                'header' => Mage::helper('bs_docwise')->__('Date'),
                'index'  => 'exam_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'exam_type',
            array(
                'header' => Mage::helper('bs_docwise')->__('Exam Type'),
                'index'  => 'exam_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_docwise')->convertOptions(
                    Mage::getModel('bs_docwise/exam_attribute_source_examtype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('catalog')->__('Position'),
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
     * Retrieve selected products
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedExams()
    {
        $exams = $this->getFilefolderExams();
        if (!is_array($exams)) {
            $exams = array_keys($this->getSelectedExams());
        }
        return $exams;
    }

    /**
     * Retrieve selected products
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedExams()
    {
        $exams = array();
        $selected = Mage::registry('current_filefolder')->getSelectedExams();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $exam) {
            $exams[$exam->getId()] = array('position' => $exam->getPosition());
        }
        return $exams;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Logistics_Model_Product
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
            '*/*/examsGrid',
            array(
                'id' => $this->getFilefolder()->getId()
            )
        );
    }

    /**
     * get the current file folder
     *
     * @access public
     * @return BS_Logistics_Model_Filefolder
     * @author Bui Phong
     */
    public function getFilefolder()
    {
        return Mage::registry('current_filefolder');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_exams') {
            $examIds = $this->_getSelectedExams();
            if (empty($examIds)) {
                $examIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $examIds));
            } else {
                if ($examIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $examIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
