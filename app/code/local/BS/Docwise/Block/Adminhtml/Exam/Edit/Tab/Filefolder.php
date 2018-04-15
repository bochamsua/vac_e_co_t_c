<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam - product relation edit block
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Filefolder extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('filefolder_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getExam()->getId()) {
            $this->setDefaultFilter(array('in_filefolders'=>1));
        }
    }


    /**
     * prepare the product collection
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_logistics/filefolder_collection');

        if ($this->getExam()->getId()) {
            $constraint = 'related.exam_id='.$this->getExam()->getId();
        } else {
            $constraint = 'related.exam_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_docwise/exam_filefolder')),
            'related.filefolder_id = main_table.entity_id AND '.$constraint,
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
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Product
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
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_docwise')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );

        $this->addColumn(
            'in_filefolders',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_filefolders',
                'values'=> $this->_getSelectedFilefolders(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );

        $this->addColumn(
            'filefolder_name',
            array(
                'header'    => Mage::helper('bs_logistics')->__('Name'),
                'align'     => 'left',
                'index'     => 'filefolder_name',
            )
        );


        $this->addColumn(
            'filefolder_code',
            array(
                'header' => Mage::helper('bs_logistics')->__('Code'),
                'index'  => 'filefolder_code',
                'type'=> 'text',

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
    protected function _getSelectedFilefolders()
    {
        $filefolders = $this->getExamFilefolders();
        if (!is_array($filefolders)) {
            $filefolders = array_keys($this->getSelectedFilefolders());
        }
        return $filefolders;
    }

    /**
     * Retrieve selected products
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedFilefolders()
    {
        $filefolders = array();
        $selected = Mage::registry('current_exam')->getSelectedFilefolders();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $filefolder) {
            $filefolders[$filefolder->getId()] = array('position' => $filefolder->getPosition());
        }
        return $filefolders;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Docwise_Model_Product
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
            '*/*/filefoldersGrid',
            array(
                'id' => $this->getExam()->getId()
            )
        );
    }

    /**
     * get the current exam
     *
     * @access public
     * @return BS_Docwise_Model_Exam
     * @author Bui Phong
     */
    public function getExam()
    {
        return Mage::registry('current_exam');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_filefolders') {
            $filefolderIds = $this->_getSelectedFilefolders();
            if (empty($filefolderIds)) {
                $filefolderIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $filefolderIds));
            } else {
                if ($filefolderIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $filefolderIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
