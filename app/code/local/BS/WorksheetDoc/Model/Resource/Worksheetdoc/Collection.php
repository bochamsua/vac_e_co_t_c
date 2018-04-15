<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document collection resource model
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Resource_Worksheetdoc_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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
        $this->_init('bs_worksheetdoc/worksheetdoc');
    }

    /**
     * get worksheet docs as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='wsdoc_name', $additional=array())
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
    protected function _toOptionHash($valueField='entity_id', $labelField='wsdoc_name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the worksheet filter to collection
     *
     * @access public
     * @param mixed (BS_Worksheet_Model_Worksheet|int) $worksheet
     * @return BS_WorksheetDoc_Model_Resource_Worksheetdoc_Collection
     * @author Bui Phong
     */
    public function addWorksheetFilter($worksheet)
    {
        if ($worksheet instanceof BS_Worksheet_Model_Worksheet) {
            $worksheet = $worksheet->getId();
        }
        if (!isset($this->_joinedFields['worksheet'])) {
            $this->getSelect()->join(
                array('related_worksheet' => $this->getTable('bs_worksheetdoc/worksheetdoc_worksheet')),
                'related_worksheet.worksheetdoc_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_worksheet.worksheet_id = ?', $worksheet);
            $this->_joinedFields['worksheet'] = true;
        }
        return $this;
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
}
