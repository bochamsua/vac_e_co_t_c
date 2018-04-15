<?php 
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document helper
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Helper_Coursedoc extends Mage_Core_Helper_Abstract
{

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media').DS.'coursedoc';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'coursedoc';
    }
}
