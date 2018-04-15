<?php 
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress helper
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Helper_Kstprogress extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the progresses list page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getKstprogressesUrl()
    {
        if ($listKey = Mage::getStoreConfig('bs_kst/kstprogress/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('bs_kst/kstprogress/index');
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
        return Mage::getStoreConfigFlag('bs_kst/kstprogress/breadcrumbs');
    }
}
