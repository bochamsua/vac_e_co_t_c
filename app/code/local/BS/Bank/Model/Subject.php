<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject model
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Model_Subject extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_bank_subject';
    const CACHE_TAG = 'bs_bank_subject';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_bank_subject';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'subject';

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
        $this->_init('bs_bank/subject');
    }

    /**
     * before save subject
     *
     * @access protected
     * @return BS_Bank_Model_Subject
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
     * save subject relation
     *
     * @access public
     * @return BS_Bank_Model_Subject
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_Bank_Model_Question_Collection
     * @author Bui Phong
     */
    public function getSelectedQuestionsCollection()
    {
        if (!$this->hasData('_question_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_bank/question_collection')
                        ->addFieldToFilter('subject_id', $this->getId());
                $this->setData('_question_collection', $collection);
            }
        }
        return $this->getData('_question_collection');
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
