<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor - Category relation resource model collection
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Model_Resource_Taskinstructor_Category_Collection extends Mage_Catalog_Model_Resource_Category_Collection
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
     * @return BS_Tasktraining_Model_Resource_Taskinstructor_Category_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_tasktraining/taskinstructor_category')),
                'related.category_id = e.entity_id',
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
     * @param BS_Tasktraining_Model_Taskinstructor | int $taskinstructor
     * @return BS_Tasktraining_Model_Resource_Taskinstructor_Category_Collection
     * @author Bui Phong
     */
    public function addTaskinstructorFilter($taskinstructor)
    {
        if ($taskinstructor instanceof BS_Tasktraining_Model_Taskinstructor) {
            $taskinstructor = $taskinstructor->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.taskinstructor_id = ?', $taskinstructor);
        return $this;
    }
}
