<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document - worksheet relation model
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Resource_Worksheetdoc_Worksheet extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Bui Phong
     */
    protected function  _construct()
    {
        $this->_init('bs_worksheetdoc/worksheetdoc_worksheet', 'rel_id');
    }
    /**
     * Save worksheet doc - worksheet relations
     *
     * @access public
     * @param BS_WorksheetDoc_Model_Worksheetdoc $worksheetdoc
     * @param array $data
     * @return BS_WorksheetDoc_Model_Resource_Worksheetdoc_Worksheet
     * @author Bui Phong
     */
    public function saveWorksheetdocRelation($worksheetdoc, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('worksheetdoc_id=?', $worksheetdoc->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $worksheetId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'worksheetdoc_id' => $worksheetdoc->getId(),
                    'worksheet_id'    => $worksheetId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  worksheet - worksheet doc relations
     *
     * @access public
     * @param BS_Worksheet_Model_Worksheet $prooduct
     * @param array $data
     * @return BS_WorksheetDoc_Model_Resource_Worksheetdoc_Worksheet
     * @@author Bui Phong
     */
    public function saveWorksheetRelation($worksheet, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('worksheet_id=?', $worksheet->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $worksheetdocId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'worksheetdoc_id' => $worksheetdocId,
                    'worksheet_id'    => $worksheet->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
