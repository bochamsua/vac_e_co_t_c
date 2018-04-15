<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer model
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Model_Answer extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_exam_answer';
    const CACHE_TAG = 'bs_exam_answer';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_exam_answer';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'answer';

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
        $this->_init('bs_exam/answer');
    }

    /**
     * before save answer
     *
     * @access protected
     * @return BS_Exam_Model_Answer
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
     * save answer relation
     *
     * @access public
     * @return BS_Exam_Model_Answer
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
     * @return null|BS_Exam_Model_Question
     * @author Bui Phong
     */
    public function getParentQuestion()
    {
        if (!$this->hasData('_parent_question')) {
            if (!$this->getQuestionId()) {
                return null;
            } else {
                $question = Mage::getModel('bs_exam/question')
                    ->load($this->getQuestionId());
                if ($question->getId()) {
                    $this->setData('_parent_question', $question);
                } else {
                    $this->setData('_parent_question', null);
                }
            }
        }
        return $this->getData('_parent_question');
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
