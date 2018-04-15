<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Score model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Score extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_docwise_score';
    const CACHE_TAG = 'bs_docwise_score';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_docwise_score';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'score';

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
        $this->_init('bs_docwise/score');
    }

    /**
     * before save score
     *
     * @access protected
     * @return BS_Docwise_Model_Score
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
     * save score relation
     *
     * @access public
     * @return BS_Docwise_Model_Score
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_Docwise_Model_Exam
     * @author Bui Phong
     */
    public function getParentExam()
    {
        if (!$this->hasData('_parent_exam')) {
            if (!$this->getExamId()) {
                return null;
            } else {
                $exam = Mage::getModel('bs_docwise/exam')
                    ->load($this->getExamId());
                if ($exam->getId()) {
                    $this->setData('_parent_exam', $exam);
                } else {
                    $this->setData('_parent_exam', null);
                }
            }
        }
        return $this->getData('_parent_exam');
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
