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
 * Worksheet collection resource model
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Model_Resource_Worksheet_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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
        $this->_init('bs_worksheet/worksheet');
    }

    /**
     * get worksheets as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='ws_name', $additional=array())
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
    protected function _toOptionHash($valueField='entity_id', $labelField='ws_name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the curriculum filter to collection
     *
     * @access public
     * @param mixed (BS_Traininglist_Model_Curriculum|int) $curriculum
     * @return BS_Worksheet_Model_Resource_Worksheet_Collection
     * @author Bui Phong
     */
    public function addCurriculumFilter($curriculum)
    {
        if ($curriculum instanceof BS_Traininglist_Model_Curriculum) {
            $curriculum = $curriculum->getId();
        }
        if (!isset($this->_joinedFields['curriculum'])) {
            $this->getSelect()->join(
                array('related_curriculum' => $this->getTable('bs_worksheet/worksheet_curriculum')),
                'related_curriculum.worksheet_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_curriculum.curriculum_id = ?', $curriculum);
            $this->_joinedFields['curriculum'] = true;
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
