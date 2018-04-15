<?php 
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Docwise Document helper
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Helper_Docwisement extends Mage_Core_Helper_Abstract
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
        return Mage::getBaseDir('media').DS.'docwisement'.DS.'file';
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
        return Mage::getBaseUrl('media').'docwisement'.'/'.'file';
    }

    public function getSelectedExams(BS_Docwise_Model_Docwisement $docwise)
    {
        if (!$docwise->hasSelectedExams()) {
            $exams = array();
            foreach ($this->getSelectedExamsCollection($docwise) as $exam) {
                $exams[] = $exam;
            }
            $docwise->setSelectedExams($exams);
        }
        return $docwise->getData('selected_exams');
    }


    public function getSelectedExamsCollection(BS_Docwise_Model_Docwisement $docwise)
    {
        $collection = Mage::getResourceSingleton('bs_docwise/exam_collection')
            ->addDocwisementFilter($docwise);
        return $collection;
    }

}
