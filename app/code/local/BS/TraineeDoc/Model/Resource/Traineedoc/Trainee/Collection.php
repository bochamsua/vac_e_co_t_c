<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document - trainee relation resource model collection
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Model_Resource_Traineedoc_Trainee_Collection extends BS_Trainee_Model_Resource_Trainee_Collection
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
     * @return BS_TraineeDoc_Model_Resource_Traineedoc_Trainee_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_traineedoc/traineedoc_trainee')),
                'related.trainee_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add trainee document filter
     *
     * @access public
     * @param BS_TraineeDoc_Model_Traineedoc | int $traineedoc
     * @return BS_TraineeDoc_Model_Resource_Traineedoc_Trainee_Collection
     * @author Bui Phong
     */
    public function addTraineedocFilter($traineedoc)
    {
        if ($traineedoc instanceof BS_TraineeDoc_Model_Traineedoc) {
            $traineedoc = $traineedoc->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.traineedoc_id = ?', $traineedoc);
        return $this;
    }
}
