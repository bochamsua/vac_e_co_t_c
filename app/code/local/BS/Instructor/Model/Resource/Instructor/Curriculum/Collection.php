<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor - curriculum relation resource model collection
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Resource_Instructor_Curriculum_Collection extends BS_Traininglist_Model_Resource_Curriculum_Collection
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
     * @return BS_Instructor_Model_Resource_Instructor_Curriculum_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_instructor/instructor_curriculum')),
                'related.curriculum_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add instructor filter
     *
     * @access public
     * @param BS_Instructor_Model_Instructor | int $instructor
     * @return BS_Instructor_Model_Resource_Instructor_Curriculum_Collection
     * @author Bui Phong
     */
    public function addInstructorFilter($instructor)
    {
        if ($instructor instanceof BS_Instructor_Model_Instructor) {
            $instructor = $instructor->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.instructor_id = ?', $instructor);
        return $this;
    }
}
