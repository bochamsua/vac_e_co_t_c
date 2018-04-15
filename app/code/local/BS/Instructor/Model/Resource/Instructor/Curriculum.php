<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor - curriculum relation model
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Resource_Instructor_Curriculum extends Mage_Core_Model_Resource_Db_Abstract
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
        $this->_init('bs_instructor/instructor_curriculum', 'rel_id');
    }
    /**
     * Save instructor - curriculum relations
     *
     * @access public
     * @param BS_Instructor_Model_Instructor $instructor
     * @param array $data
     * @return BS_Instructor_Model_Resource_Instructor_Curriculum
     * @author Bui Phong
     */
    public function saveInstructorRelation($instructor, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('instructor_id=?', $instructor->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $curriculumId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'instructor_id' => $instructor->getId(),
                    'curriculum_id'    => $curriculumId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  curriculum - instructor relations
     *
     * @access public
     * @param Mage_Catalog_Model_Curriculum $prooduct
     * @param array $data
     * @return BS_Instructor_Model_Resource_Instructor_Curriculum
     * @@author Bui Phong
     */
    public function saveCurriculumRelation($curriculum, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('curriculum_id=?', $curriculum->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $instructorId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'instructor_id' => $instructorId,
                    'curriculum_id'    => $curriculum->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
