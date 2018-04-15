<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document - worksheet relation resource model collection
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Resource_Worksheetdoc_Worksheet_Collection extends BS_Worksheet_Model_Resource_Worksheet_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return BS_WorksheetDoc_Model_Resource_Worksheetdoc_Worksheet_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_worksheetdoc/worksheetdoc_worksheet')),
                'related.worksheet_id = main_table.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add worksheet doc filter
     *
     * @access public
     * @param BS_WorksheetDoc_Model_Worksheetdoc | int $worksheetdoc
     * @return BS_WorksheetDoc_Model_Resource_Worksheetdoc_Worksheet_Collection
     * @author Bui Phong
     */
    public function addWorksheetdocFilter($worksheetdoc)
    {
        if ($worksheetdoc instanceof BS_WorksheetDoc_Model_Worksheetdoc) {
            $worksheetdoc = $worksheetdoc->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.worksheetdoc_id = ?', $worksheetdoc);
        return $this;
    }
}
