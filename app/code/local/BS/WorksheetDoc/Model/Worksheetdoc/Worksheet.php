<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document worksheet model
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Worksheetdoc_Worksheet extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->_init('bs_worksheetdoc/worksheetdoc_worksheet');
    }

    /**
     * Save data for worksheet doc-worksheet relation
     * @access public
     * @param  BS_WorksheetDoc_Model_Worksheetdoc $worksheetdoc
     * @return BS_WorksheetDoc_Model_Worksheetdoc_Worksheet
     * @author Bui Phong
     */
    public function saveWorksheetdocRelation($worksheetdoc)
    {
        $data = $worksheetdoc->getWorksheetsData();
        if (!is_null($data)) {
            $this->_getResource()->saveWorksheetdocRelation($worksheetdoc, $data);
        }
        return $this;
    }

    /**
     * get worksheets for worksheet doc
     *
     * @access public
     * @param BS_WorksheetDoc_Model_Worksheetdoc $worksheetdoc
     * @return BS_WorksheetDoc_Model_Resource_Worksheetdoc_Worksheet_Collection
     * @author Bui Phong
     */
    public function getWorksheetCollection($worksheetdoc)
    {
        $collection = Mage::getResourceModel('bs_worksheetdoc/worksheetdoc_worksheet_collection')
            ->addWorksheetdocFilter($worksheetdoc);
        return $collection;
    }
}
