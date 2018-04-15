<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Question view block
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Question_View extends Mage_Core_Block_Template
{
    /**
     * get the current question
     *
     * @access public
     * @return mixed (BS_Bank_Model_Question|null)
     * @author Bui Phong
     */
    public function getCurrentQuestion()
    {
        return Mage::registry('current_question');
    }
}
