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
 * Worksheet - curriculum relation model
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Model_Resource_Worksheet_Curriculum extends Mage_Core_Model_Resource_Db_Abstract
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
        $this->_init('bs_worksheet/worksheet_curriculum', 'rel_id');
    }
    /**
     * Save worksheet - curriculum relations
     *
     * @access public
     * @param BS_Worksheet_Model_Worksheet $worksheet
     * @param array $data
     * @return BS_Worksheet_Model_Resource_Worksheet_Curriculum
     * @author Bui Phong
     */
    public function saveWorksheetRelation($worksheet, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('worksheet_id=?', $worksheet->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $curriculumId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'worksheet_id' => $worksheet->getId(),
                    'curriculum_id'    => $curriculumId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  curriculum - worksheet relations
     *
     * @access public
     * @param BS_Traininglist_Model_Curriculum $prooduct
     * @param array $data
     * @return BS_Worksheet_Model_Resource_Worksheet_Curriculum
     * @@author Bui Phong
     */
    public function saveCurriculumRelation($curriculum, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('curriculum_id=?', $curriculum->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $worksheetId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'worksheet_id' => $worksheetId,
                    'curriculum_id'    => $curriculum->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
