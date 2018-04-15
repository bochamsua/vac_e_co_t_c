<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document trainee model
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Model_Traineedoc_Trainee extends Mage_Core_Model_Abstract
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
        $this->_init('bs_traineedoc/traineedoc_trainee');
    }

    /**
     * Save data for trainee document-trainee relation
     * @access public
     * @param  BS_TraineeDoc_Model_Traineedoc $traineedoc
     * @return BS_TraineeDoc_Model_Traineedoc_Trainee
     * @author Bui Phong
     */
    public function saveTraineedocRelation($traineedoc)
    {
        $data = $traineedoc->getTraineesData();
        if (!is_null($data)) {
            $this->_getResource()->saveTraineedocRelation($traineedoc, $data);
        }
        return $this;
    }

    /**
     * get trainees for trainee document
     *
     * @access public
     * @param BS_TraineeDoc_Model_Traineedoc $traineedoc
     * @return BS_TraineeDoc_Model_Resource_Traineedoc_Trainee_Collection
     * @author Bui Phong
     */
    public function getTraineeCollection($traineedoc)
    {
        $collection = Mage::getResourceModel('bs_traineedoc/traineedoc_trainee_collection')
            ->addTraineedocFilter($traineedoc);
        return $collection;
    }
}
