<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee helper
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Helper_Trainee extends BS_TraineeDoc_Helper_Data
{

    /**
     * get the selected trainee document for a trainee
     *
     * @access public
     * @param BS_Trainee_Model_Trainee $trainee
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedTraineedocs(BS_Trainee_Model_Trainee $trainee)
    {
        if (!$trainee->hasSelectedTraineedocs()) {
            $traineedocs = array();
            foreach ($this->getSelectedTraineedocsCollection($trainee) as $traineedoc) {
                $traineedocs[] = $traineedoc;
            }
            $trainee->setSelectedTraineedocs($traineedocs);
        }
        return $trainee->getData('selected_traineedocs');
    }

    /**
     * get trainee document collection for a trainee
     *
     * @access public
     * @param BS_Trainee_Model_Trainee $trainee
     * @return BS_TraineeDoc_Model_Resource_Traineedoc_Collection
     * @author Bui Phong
     */
    public function getSelectedTraineedocsCollection(BS_Trainee_Model_Trainee $trainee)
    {
        $collection = Mage::getResourceSingleton('bs_traineedoc/traineedoc_collection')
            ->addTraineeFilter($trainee);
        return $collection;
    }
}
