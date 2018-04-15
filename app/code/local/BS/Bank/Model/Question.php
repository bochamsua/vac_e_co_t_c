<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Question model
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Model_Question extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_bank_question';
    const CACHE_TAG = 'bs_bank_question';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_bank_question';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'question';

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
        $this->_init('bs_bank/question');
    }

    /**
     * before save question
     *
     * @access protected
     * @return BS_Bank_Model_Question
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
     * save question relation
     *
     * @access public
     * @return BS_Bank_Model_Question
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
     * @return null|BS_Bank_Model_Subject
     * @author Bui Phong
     */
    public function getParentSubject()
    {
        if (!$this->hasData('_parent_subject')) {
            if (!$this->getSubjectId()) {
                return null;
            } else {
                $subject = Mage::getModel('bs_bank/subject')
                    ->load($this->getSubjectId());
                if ($subject->getId()) {
                    $this->setData('_parent_subject', $subject);
                } else {
                    $this->setData('_parent_subject', null);
                }
            }
        }
        return $this->getData('_parent_subject');
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
