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
 * Instructor Document - instructor relation resource model collection
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Model_Resource_Instructordoc_Instructor_Collection extends BS_Instructor_Model_Resource_Instructor_Collection
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
     * @return BS_Material_Model_Resource_Instructordoc_Instructor_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_material/instructordoc_instructor')),
                'related.instructor_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add instructor doc filter
     *
     * @access public
     * @param BS_Material_Model_Instructordoc | int $instructordoc
     * @return BS_Material_Model_Resource_Instructordoc_Instructor_Collection
     * @author Bui Phong
     */
    public function addInstructordocFilter($instructordoc)
    {
        if ($instructordoc instanceof BS_Material_Model_Instructordoc) {
            $instructordoc = $instructordoc->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.instructordoc_id = ?', $instructordoc);
        return $this;
    }
}
