<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document - trainee relation model
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Model_Resource_Traineedoc_Trainee extends Mage_Core_Model_Resource_Db_Abstract
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
        $this->_init('bs_traineedoc/traineedoc_trainee', 'rel_id');
    }
    /**
     * Save trainee document - trainee relations
     *
     * @access public
     * @param BS_TraineeDoc_Model_Traineedoc $traineedoc
     * @param array $data
     * @return BS_TraineeDoc_Model_Resource_Traineedoc_Trainee
     * @author Bui Phong
     */
    public function saveTraineedocRelation($traineedoc, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('traineedoc_id=?', $traineedoc->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $traineeId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'traineedoc_id' => $traineedoc->getId(),
                    'trainee_id'    => $traineeId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  trainee - trainee document relations
     *
     * @access public
     * @param BS_Trainee_Model_Trainee $prooduct
     * @param array $data
     * @return BS_TraineeDoc_Model_Resource_Traineedoc_Trainee
     * @@author Bui Phong
     */
    public function saveTraineeRelation($trainee, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('trainee_id=?', $trainee->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $traineedocId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'traineedoc_id' => $traineedocId,
                    'trainee_id'    => $trainee->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
