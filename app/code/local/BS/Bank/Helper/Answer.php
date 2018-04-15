<?php 
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer helper
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Helper_Answer extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the answers list page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getAnswersUrl()
    {
        if ($listKey = Mage::getStoreConfig('bs_bank/answer/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('bs_bank/answer/index');
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
        return Mage::getStoreConfigFlag('bs_bank/answer/breadcrumbs');
    }

    /**
     * check if the rss for answer is enabled
     *
     * @access public
     * @return bool
     * @author Bui Phong
     */
    public function isRssEnabled()
    {
        return  Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('bs_bank/answer/rss');
    }

    /**
     * get the link to the answer rss list
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getRssUrl()
    {
        return Mage::getUrl('bs_bank/answer/rss');
    }
}
