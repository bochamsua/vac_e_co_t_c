<?php
/**
 * BS_ImportInstructor extension
 * 
 * @category       BS
 * @package        BS_ImportInstructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Instructor model
 *
 * @category    BS
 * @package     BS_ImportInstructor
 * @author Bui Phong
 */
class BS_ImportInstructor_Model_Importinstructor extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_importinstructor_importinstructor';
    const CACHE_TAG = 'bs_importinstructor_importinstructor';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_importinstructor_importinstructor';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'importinstructor';

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
        $this->_init('bs_importinstructor/importinstructor');
    }

    /**
     * before save import instructor
     *
     * @access protected
     * @return BS_ImportInstructor_Model_Importinstructor
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
     * save import instructor relation
     *
     * @access public
     * @return BS_ImportInstructor_Model_Importinstructor
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
    
}
