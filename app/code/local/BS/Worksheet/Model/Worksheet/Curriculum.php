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
 * Worksheet curriculum model
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Model_Worksheet_Curriculum extends Mage_Core_Model_Abstract
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
        $this->_init('bs_worksheet/worksheet_curriculum');
    }

    /**
     * Save data for worksheet-curriculum relation
     * @access public
     * @param  BS_Worksheet_Model_Worksheet $worksheet
     * @return BS_Worksheet_Model_Worksheet_Curriculum
     * @author Bui Phong
     */
    public function saveWorksheetRelation($worksheet)
    {
        $data = $worksheet->getCurriculumsData();
        if (!is_null($data)) {
            $this->_getResource()->saveWorksheetRelation($worksheet, $data);
        }
        return $this;
    }

    /**
     * get curriculums for worksheet
     *
     * @access public
     * @param BS_Worksheet_Model_Worksheet $worksheet
     * @return BS_Worksheet_Model_Resource_Worksheet_Curriculum_Collection
     * @author Bui Phong
     */
    public function getCurriculumCollection($worksheet)
    {
        $collection = Mage::getResourceModel('bs_worksheet/worksheet_curriculum_collection')
            ->addWorksheetFilter($worksheet);
        return $collection;
    }
}
