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
 * Instructor Document collection resource model
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Model_Resource_Instructordoc_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('bs_material/instructordoc');
    }

    /**
     * get instructor docs as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='idoc_name', $additional=array())
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='idoc_name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the instructor filter to collection
     *
     * @access public
     * @param mixed (BS_Instructor_Model_Instructor|int) $instructor
     * @return BS_Material_Model_Resource_Instructordoc_Collection
     * @author Bui Phong
     */
    public function addInstructorFilter($instructor)
    {
        if ($instructor instanceof BS_Instructor_Model_Instructor) {
            $instructor = $instructor->getId();
        }
        if (!isset($this->_joinedFields['instructor'])) {
            $this->getSelect()->join(
                array('related_instructor' => $this->getTable('bs_material/instructordoc_instructor')),
                'related_instructor.instructordoc_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_instructor.instructor_id = ?', $instructor);
            $this->_joinedFields['instructor'] = true;
        }
        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author Bui Phong
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
