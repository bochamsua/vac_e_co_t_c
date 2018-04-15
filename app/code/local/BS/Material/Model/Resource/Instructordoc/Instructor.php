<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document - instructor relation model
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Model_Resource_Instructordoc_Instructor extends Mage_Core_Model_Resource_Db_Abstract
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
        $this->_init('bs_material/instructordoc_instructor', 'rel_id');
    }
    /**
     * Save instructor doc - instructor relations
     *
     * @access public
     * @param BS_Material_Model_Instructordoc $instructordoc
     * @param array $data
     * @return BS_Material_Model_Resource_Instructordoc_Instructor
     * @author Bui Phong
     */
    public function saveInstructordocRelation($instructordoc, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('instructordoc_id=?', $instructordoc->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $instructorId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'instructordoc_id' => $instructordoc->getId(),
                    'instructor_id'    => $instructorId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  instructor - instructor doc relations
     *
     * @access public
     * @param BS_Instructor_Model_Instructor $prooduct
     * @param array $data
     * @return BS_Material_Model_Resource_Instructordoc_Instructor
     * @@author Bui Phong
     */
    public function saveInstructorRelation($instructor, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('instructor_id=?', $instructor->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $instructordocId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'instructordoc_id' => $instructordocId,
                    'instructor_id'    => $instructor->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
