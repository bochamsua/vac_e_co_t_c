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
 * Instructor Document instructor model
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Model_Instructordoc_Instructor extends Mage_Core_Model_Abstract
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
        $this->_init('bs_material/instructordoc_instructor');
    }

    /**
     * Save data for instructor doc-instructor relation
     * @access public
     * @param  BS_Material_Model_Instructordoc $instructordoc
     * @return BS_Material_Model_Instructordoc_Instructor
     * @author Bui Phong
     */
    public function saveInstructordocRelation($instructordoc)
    {
        $data = $instructordoc->getInstructorsData();
        if (!is_null($data)) {
            $this->_getResource()->saveInstructordocRelation($instructordoc, $data);
        }
        return $this;
    }

    /**
     * get instructors for instructor doc
     *
     * @access public
     * @param BS_Material_Model_Instructordoc $instructordoc
     * @return BS_Material_Model_Resource_Instructordoc_Instructor_Collection
     * @author Bui Phong
     */
    public function getInstructorCollection($instructordoc)
    {
        $collection = Mage::getResourceModel('bs_material/instructordoc_instructor_collection')
            ->addInstructordocFilter($instructordoc);
        return $collection;
    }
}
