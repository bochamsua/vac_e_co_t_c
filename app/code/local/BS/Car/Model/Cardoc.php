<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * CAR Document model
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Model_Cardoc extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_car_cardoc';
    const CACHE_TAG = 'bs_car_cardoc';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_car_cardoc';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'cardoc';

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
        $this->_init('bs_car/cardoc');
    }

    /**
     * before save car document
     *
     * @access protected
     * @return BS_Car_Model_Cardoc
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
     * save car document relation
     *
     * @access public
     * @return BS_Car_Model_Cardoc
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
     * @return null|BS_Car_Model_Qacar
     * @author Bui Phong
     */
    public function getParentQacar()
    {
        if (!$this->hasData('_parent_qacar')) {
            if (!$this->getQacarId()) {
                return null;
            } else {
                $qacar = Mage::getModel('bs_car/qacar')
                    ->load($this->getQacarId());
                if ($qacar->getId()) {
                    $this->setData('_parent_qacar', $qacar);
                } else {
                    $this->setData('_parent_qacar', null);
                }
            }
        }
        return $this->getData('_parent_qacar');
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
