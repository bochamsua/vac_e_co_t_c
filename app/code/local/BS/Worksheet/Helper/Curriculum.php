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
 * Curriculum helper
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Helper_Curriculum extends BS_Worksheet_Helper_Data
{

    /**
     * get the selected worksheets for a curriculum
     *
     * @access public
     * @param BS_Traininglist_Model_Curriculum $curriculum
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedWorksheets(BS_Traininglist_Model_Curriculum $curriculum)
    {
        if (!$curriculum->hasSelectedWorksheets()) {
            $worksheets = array();
            foreach ($this->getSelectedWorksheetsCollection($curriculum) as $worksheet) {
                $worksheets[] = $worksheet;
            }
            $curriculum->setSelectedWorksheets($worksheets);
        }
        return $curriculum->getData('selected_worksheets');
    }

    /**
     * get worksheet collection for a curriculum
     *
     * @access public
     * @param BS_Traininglist_Model_Curriculum $curriculum
     * @return BS_Worksheet_Model_Resource_Worksheet_Collection
     * @author Bui Phong
     */
    public function getSelectedWorksheetsCollection(BS_Traininglist_Model_Curriculum $curriculum)
    {
        $collection = Mage::getResourceSingleton('bs_worksheet/worksheet_collection')
            ->addCurriculumFilter($curriculum);
        return $collection;
    }
}
