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
 * Instructor helper
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Helper_Instructor extends BS_Material_Helper_Data
{

    /**
     * get the selected instructor docs for a instructor
     *
     * @access public
     * @param BS_Instructor_Model_Instructor $instructor
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedInstructordocs(BS_Instructor_Model_Instructor $instructor)
    {
        if (!$instructor->hasSelectedInstructordocs()) {
            $instructordocs = array();
            foreach ($this->getSelectedInstructordocsCollection($instructor) as $instructordoc) {
                $instructordocs[] = $instructordoc;
            }
            $instructor->setSelectedInstructordocs($instructordocs);
        }
        return $instructor->getData('selected_instructordocs');
    }

    /**
     * get instructor doc collection for a instructor
     *
     * @access public
     * @param BS_Instructor_Model_Instructor $instructor
     * @return BS_Material_Model_Resource_Instructordoc_Collection
     * @author Bui Phong
     */
    public function getSelectedInstructordocsCollection(BS_Instructor_Model_Instructor $instructor)
    {
        $collection = Mage::getResourceSingleton('bs_material/instructordoc_collection')
            ->addInstructorFilter($instructor);
        return $collection;
    }
}
