<?php 
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject helper
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Helper_Kstsubject extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the subjects list page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getKstsubjectsUrl()
    {
        if ($listKey = Mage::getStoreConfig('bs_kst/kstsubject/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('bs_kst/kstsubject/index');
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
        return Mage::getStoreConfigFlag('bs_kst/kstsubject/breadcrumbs');
    }
}
