<?php 
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject helper
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Helper_Subject extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the subject list page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getSubjectsUrl()
    {
        if ($listKey = Mage::getStoreConfig('bs_bank/subject/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('bs_bank/subject/index');
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
        return Mage::getStoreConfigFlag('bs_bank/subject/breadcrumbs');
    }
}
