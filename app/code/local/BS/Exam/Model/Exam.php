<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam model
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Model_Exam extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_exam_exam';
    const CACHE_TAG = 'bs_exam_exam';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_exam_exam';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'exam';

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
        $this->_init('bs_exam/exam');
    }

    /**
     * before save exam
     *
     * @access protected
     * @return BS_Exam_Model_Exam
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
     * save exam relation
     *
     * @access public
     * @return BS_Exam_Model_Exam
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
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
    
    /**
      * get Subject
      *
      * @access public
      * @return array
      * @author Bui Phong
      */
    public function getSubjectIds()
    {
        if (!$this->getData('subject_ids')) {
            return explode(',', $this->getData('subject_ids'));
        }
        return $this->getData('subject_ids');
    }
    /**
      * get Examiner
      *
      * @access public
      * @return array
      * @author Bui Phong
      */
    public function getExaminers()
    {
        if (!$this->getData('examiners')) {
            return explode(',', $this->getData('examiners'));
        }
        return $this->getData('examiners');
    }
}
