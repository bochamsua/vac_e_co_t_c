<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet helper
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Helper_Worksheet extends BS_WorksheetDoc_Helper_Data
{

    /**
     * get the selected worksheet docs for a worksheet
     *
     * @access public
     * @param BS_Worksheet_Model_Worksheet $worksheet
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedWorksheetdocs(BS_Worksheet_Model_Worksheet $worksheet)
    {
        if (!$worksheet->hasSelectedWorksheetdocs()) {
            $worksheetdocs = array();
            foreach ($this->getSelectedWorksheetdocsCollection($worksheet) as $worksheetdoc) {
                $worksheetdocs[] = $worksheetdoc;
            }
            $worksheet->setSelectedWorksheetdocs($worksheetdocs);
        }
        return $worksheet->getData('selected_worksheetdocs');
    }

    /**
     * get worksheet doc collection for a worksheet
     *
     * @access public
     * @param BS_Worksheet_Model_Worksheet $worksheet
     * @return BS_WorksheetDoc_Model_Resource_Worksheetdoc_Collection
     * @author Bui Phong
     */
    public function getSelectedWorksheetdocsCollection(BS_Worksheet_Model_Worksheet $worksheet)
    {
        $collection = Mage::getResourceSingleton('bs_worksheetdoc/worksheetdoc_collection')
            ->addWorksheetFilter($worksheet);
        return $collection;
    }
}
