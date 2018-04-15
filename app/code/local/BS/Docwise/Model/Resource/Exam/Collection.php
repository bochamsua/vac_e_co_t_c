<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam collection resource model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Resource_Exam_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('bs_docwise/exam');
    }

    /**
     * get exams as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='exam_code', $additional=array())
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='exam_code')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author Bui Phong
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }

    /**
     * add the product filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Product|int) $product
     * @return BS_Docwise_Model_Resource_Exam_Collection
     * @author Bui Phong
     */
    public function addDocwisementFilter($docwise)
    {
        if ($docwise instanceof BS_Docwise_Model_Docwisement) {
            $docwise = $docwise->getId();
        }
        if (!isset($this->_joinedFields['docwisement'])) {
            $this->getSelect()->join(
                array('related_docwisement' => $this->getTable('bs_docwise/exam_docwisement')),
                'related_docwisement.exam_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_docwisement.docwisement_id = ?', $docwise);
            $this->_joinedFields['docwisement'] = true;
        }
        return $this;
    }

    public function addFilefolderFilter($filefolder)
    {
        if ($filefolder instanceof BS_Logistics_Model_Filefolder) {
            $filefolder = $filefolder->getId();
        }
        if (!isset($this->_joinedFields['filefolder'])) {
            $this->getSelect()->join(
                array('related_filefolder' => $this->getTable('bs_docwise/exam_filefolder')),
                'related_filefolder.exam_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_filefolder.filefolder_id = ?', $filefolder);
            $this->_joinedFields['filefolder'] = true;
        }
        return $this;
    }

}
