<?php 
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Question helper
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Helper_Question extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the questions list page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getQuestionsUrl()
    {
        if ($listKey = Mage::getStoreConfig('bs_bank/question/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('bs_bank/question/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Bui Phong
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('bs_bank/question/breadcrumbs');
    }
}
