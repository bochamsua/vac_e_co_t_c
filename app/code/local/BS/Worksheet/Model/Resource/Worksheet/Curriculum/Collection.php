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
 * Worksheet - curriculum relation resource model collection
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Model_Resource_Worksheet_Curriculum_Collection extends BS_Traininglist_Model_Resource_Curriculum_Collection
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
     * @return BS_Worksheet_Model_Resource_Worksheet_Curriculum_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_worksheet/worksheet_curriculum')),
                'related.curriculum_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add worksheet filter
     *
     * @access public
     * @param BS_Worksheet_Model_Worksheet | int $worksheet
     * @return BS_Worksheet_Model_Resource_Worksheet_Curriculum_Collection
     * @author Bui Phong
     */
    public function addWorksheetFilter($worksheet)
    {
        if ($worksheet instanceof BS_Worksheet_Model_Worksheet) {
            $worksheet = $worksheet->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.worksheet_id = ?', $worksheet);
        return $this;
    }
}
