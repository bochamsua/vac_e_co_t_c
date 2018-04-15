<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document model
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Model_Traineedoc extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_traineedoc_traineedoc';
    const CACHE_TAG = 'bs_traineedoc_traineedoc';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_traineedoc_traineedoc';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'traineedoc';
    protected $_traineeInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bs_traineedoc/traineedoc');
    }

    /**
     * before save trainee document
     *
     * @access protected
     * @return BS_TraineeDoc_Model_Traineedoc
     * @author Bui Phong
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save trainee document relation
     *
     * @access public
     * @return BS_TraineeDoc_Model_Traineedoc
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getTraineeInstance()->saveTraineedocRelation($this);
        return parent::_afterSave();
    }

    /**
     * get trainee relation model
     *
     * @access public
     * @return BS_TraineeDoc_Model_Traineedoc_Trainee
     * @author Bui Phong
     */
    public function getTraineeInstance()
    {
        if (!$this->_traineeInstance) {
            $this->_traineeInstance = Mage::getSingleton('bs_traineedoc/traineedoc_trainee');
        }
        return $this->_traineeInstance;
    }

    /**
     * get selected trainees array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedTrainees()
    {
        if (!$this->hasSelectedTrainees()) {
            $trainees = array();
            foreach ($this->getSelectedTraineesCollection() as $trainee) {
                $trainees[] = $trainee;
            }
            $this->setSelectedTrainees($trainees);
        }
        return $this->getData('selected_trainees');
    }

    /**
     * Retrieve collection selected trainees
     *
     * @access public
     * @return BS_TraineeDoc_Resource_Traineedoc_Trainee_Collection
     * @author Bui Phong
     */
    public function getSelectedTraineesCollection()
    {
        $collection = $this->getTraineeInstance()->getTraineeCollection($this);
        return $collection;
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
