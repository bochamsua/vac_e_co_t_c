<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Logistics default helper
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Bui Phong
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function getNextContainerCode($workshopId, $typeId){
        $containers = Mage::getModel('bs_logistics/wgroupitem')->getCollection()
            ->addFieldToFilter('workshop_id', $workshopId)
            ->addFieldToFilter('grouptype_id', $typeId)
            ->setOrder('code', 'DESC')
        ;

        if($containers->getFirstItem()->getId()){
            $lastCode = $containers->getFirstItem()->getCode();
            $format = preg_replace('/[\d]{1,3}/','',$lastCode);
            $lastId = intval(preg_replace('/[^\d]/','',$lastCode));
            $nextId = $lastId + 1;

            /*if($nextId < 10){
                $nextId ='0'.$nextId;
            }*/

            return $format.$nextId;
        }

        $workshop = Mage::getModel('bs_logistics/workshop')->load($workshopId);
        $workshopCode = $workshop->getWorkshopCode();

        $type = Mage::getModel('bs_logistics/grouptype')->load($typeId);
        $typeCode = $type->getCode();

        return 'TC-'.$workshopCode.'-'.$typeCode.'1';




    }

    public function getNextEquipmentCode($workshopId, $typeId, $containerId = null){
        $items = Mage::getModel('bs_logistics/wtool')->getCollection()
            ->addFieldToFilter('workshop_id', $workshopId)


        ;

        if($typeId > 0){
            $items->addFieldToFilter('grouptype_id', $typeId);
        }

        if($containerId > 0){
            $items->addFieldToFilter('wgroupitem_id', $containerId);
        }else {
            $items->addFieldToFilter('wgroupitem_id', 0);
        }
        $items->setOrder('code', 'DESC');

        if($items->getFirstItem()->getId()){
            $lastCode = $items->getFirstItem()->getCode();
            $lastCode = explode("-", $lastCode);
            $count = count($lastCode);


            $lastId = intval($lastCode[$count-1]);
            $nextId = $lastId + 1;

            unset($lastCode[$count-1]);
            $format = implode("-",$lastCode);

            /*if($nextId < 10){
                $nextId ='00'.$nextId;
            }elseif($nextId < 100 && $nextId >= 10){
                $nextId ='0'.$nextId;
            }*/

            return $format.'-'.$nextId;
        }

        $workshop = Mage::getModel('bs_logistics/workshop')->load($workshopId);
        $workshopCode = $workshop->getWorkshopCode();


        if($containerId){
            $container = Mage::getModel('bs_logistics/wgroupitem')->load($containerId);
            $result = $container->getCode();
        }else {
            $type = Mage::getModel('bs_logistics/grouptype')->load($typeId);
            $typeCode = $type->getCode();

            $result = 'TC-'.$workshopCode.'-'.$typeCode;
        }
        $result .= '-1';

        return $result;



    }

    public function getLatinIndex($integer, $upcase = true)
    {
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
        $return = '';
        while($integer > 0)
        {
            foreach($table as $rom=>$arb)
            {
                if($integer >= $arb)
                {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }
}
