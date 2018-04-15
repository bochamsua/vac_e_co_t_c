<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Model_Adminhtml_Search_Trainee extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Trainee_Model_Adminhtml_Search_Trainee
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_trainee/trainee_collection')
            ->addAttributeToFilter(array(
                array('attribute'=>'trainee_name', 'like'  => '%'.$this->getQuery().'%'),
                array('attribute'=>'vaeco_id', 'like'  => '%'.$this->getQuery().'%'),
            ))
            ->addAttributeToSelect('trainee_phone')
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $trainee) {
            $vaecoId = $trainee->getVaecoId();
            if($vaecoId == ''){
                $vaecoId = $trainee->getTraineeCode();
            }
            $phone = $trainee->getTraineePhone();
            $arr[] = array(
                'id'          => 'trainee/1/'.$trainee->getId(),
                'type'        => Mage::helper('bs_trainee')->__('Trainee'),
                'name'        => $trainee->getTraineeName(),
                'description' => $vaecoId.' - '.$phone,
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/trainee_trainee/edit',
                    array('id'=>$trainee->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
