<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam tab on product edit form
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Docwisement_Edit_Tab_Exam extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('exam_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getDocwisement()->getId()) {
            $this->setDefaultFilter(array('in_exams'=>1));
        }
    }

    /**
     * prepare the exam collection
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Catalog_Product_Edit_Tab_Exam
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_docwise/exam_collection');
        if ($this->getDocwisement()->getId()) {
            $constraint = 'related.docwisement_id='.$this->getDocwisement()->getId();
        } else {
            $constraint = 'related.docwisement_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_docwise/exam_docwisement')),
            'related.exam_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_Docwise_Block_Adminhtml_Catalog_Product_Edit_Tab_Exam
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
     * @return BS_Docwise_Block_Adminhtml_Catalog_Product_Edit_Tab_Exam
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
                'header' => Mage::helper('bs_docwise')->__('Code'),
                'align'  => 'left',
                'index'  => 'exam_code',
                'renderer' => 'bs_docwise/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/docwise_exam/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_docwise')->__('Position'),
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
     * Retrieve selected exams
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedExams()
    {
        $exams = $this->getDocwisementExams();
        if (!is_array($exams)) {
            $exams = array_keys($this->getSelectedExams());
        }
        return $exams;
    }

    /**
     * Retrieve selected exams
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedExams()
    {
        $exams = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_docwise/docwisement')->getSelectedExams(Mage::registry('current_docwisement'));
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
     * @param BS_Docwise_Model_Exam
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
                'id'=>$this->getDocwisement()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Bui Phong
     */
    public function getDocwisement()
    {
        return Mage::registry('current_docwisement');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Docwise_Block_Adminhtml_Catalog_Product_Edit_Tab_Exam
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_exams') {
            $examIds = $this->_getSelectedExams();
            if (empty($examIds)) {
                $examIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$examIds));
            } else {
                if ($examIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$examIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
