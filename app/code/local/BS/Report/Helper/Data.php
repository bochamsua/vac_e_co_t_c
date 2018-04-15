<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Report default helper
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Helper_Data extends Mage_Core_Helper_Abstract
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

    public function checkManager(){

        $currentUserId = Mage::getSingleton('admin/session')->getUser()->getId();

        $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'rate_ids')->getFirstItem();
        $ids = array();
        if($shortcut->getId()){
            $content = $shortcut->getDescription();
            $content = str_replace(" ", "", $content);

            $ids = explode(",", $content);

        }

        if(in_array($currentUserId, $ids)){
            return true;
        }

        return false;
    }
    public function checkSupervisor(){

        $currentUserId = Mage::getSingleton('admin/session')->getUser()->getId();

        $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'user_ids')->getFirstItem();
        $ids = array();
        if($shortcut->getId()){
            $content = $shortcut->getDescription();
            $content = str_replace(" ", "", $content);

            $ids = explode(",", $content);

        }

        if(in_array($currentUserId, $ids)){
            return true;
        }

        return false;
    }

    public function getReportData($requestData, $type = 'user'){
        $duration = 'Thời gian: ';

        $from = null;
        $to = null;
        if(count($requestData)){
            if(isset($requestData['from'])){
                $from = $requestData['from'];
            }

            if(isset($requestData['to'])){
                $to = $requestData['to'];
            }



        }

        $report = Mage::getModel('bs_report/report')->getCollection();



        if($type == 'supervisor'){
            $report->addFieldToFilter('supervisor_id', Mage::getSingleton('admin/session')->getUser()->getId());
        }elseif($type == 'user'){
            $report->addFieldToFilter('user_id', Mage::getSingleton('admin/session')->getUser()->getId());
        }

        if($from){
            $report->addFieldToFilter('created_at', array('from'=>$from));
            $duration .= 'Từ ngày '.Mage::getModel('core/date')->date("d/m/Y", $from);
        }
        if($to){
            $report->addFieldToFilter('created_at', array('to'=>$to));
            $duration .= ' đến ngày '.Mage::getModel('core/date')->date("d/m/Y", $to);
        }

        $result = array();
        if($report->count()){
            $userIds = array();
            foreach ($report as $r) {
                $userIds[$r->getUserId()] = 1;
            }
            $userIds = array_keys($userIds);


            if(count($userIds)){



                foreach ($userIds as $id) {

                    $user = Mage::getModel('admin/user')->load($id);
                    $name = $user->getName();



                    //Get all report by USER
                    $sreport = Mage::getModel('bs_report/report')->getCollection();
                    if($type == 'supervisor'){
                        $sreport->addFieldToFilter('supervisor_id', Mage::getSingleton('admin/session')->getUser()->getId());
                    }
                    if(count($requestData)){
                        $to = $requestData['to'];
                        $from = $requestData['from'];
                        if($from != ''){
                            $sreport->addFieldToFilter('created_at', array('from'=>$from));
                        }
                        if($to != ''){
                            $sreport->addFieldToFilter('created_at', array('to'=>$to));
                        }

                    }
                    $sreport->addFieldToFilter('user_id', $id)->setOrder('entity_id');
                    if($sreport->count()){
                        $data = array();
                        foreach ($sreport as $s) {

                            $supervisor = Mage::getModel('admin/user')->load($s->getSupervisorId());


                            $date = Mage::getModel('core/date')->date("d/m/Y", $s->getCreatedAt());
                            $exdate = Mage::getModel('core/date')->date("d/m/Y", $s->getExpectedDate());
                            $data[] = array(
                                'date'  => $date,
                                'brief' => Mage::getSingleton('bs_report/report_attribute_source_tasks')->getOptionText($s->getTctaskId()),
                                'detail'    => $s->getDetail(),
                                'supervisor'    => $supervisor->getName(),
                                'percent'   => $s->getTaskTime(),
                                'ex_date'   => '',
                                'note'  => $s->getNote()



                            );
                        }

                        $result[] = array(
                            'user'  => $name,
                            'data'  => $data
                        );

                    }
                }

            }
        }

        return array(
            'duration' => $duration,
            'data' => $result
        );
    }

    public function getReportStatistic($requestData, $type = 'user'){
        $duration = 'Thời gian: ';

        $from = null;
        $to = null;
        if(count($requestData)){
            if(isset($requestData['from'])){
                $from = $requestData['from'];
            }

            if(isset($requestData['to'])){
                $to = $requestData['to'];
            }



        }

        $report = Mage::getModel('bs_report/report')->getCollection();



        if($type == 'supervisor'){
            $report->addFieldToFilter('supervisor_id', Mage::getSingleton('admin/session')->getUser()->getId());
        }elseif($type == 'user'){
            $report->addFieldToFilter('user_id', Mage::getSingleton('admin/session')->getUser()->getId());
        }

        if($from){
            $report->addFieldToFilter('created_at', array('from'=>$from));
            $duration .= 'Từ ngày '.Mage::getModel('core/date')->date("d/m/Y", $from);
        }
        if($to){
            $report->addFieldToFilter('created_at', array('to'=>$to));
            $duration .= ' đến ngày '.Mage::getModel('core/date')->date("d/m/Y", $to);
        }

        $result = array();
        if($report->count()){
            $userIds = array();
            foreach ($report as $r) {
                $userIds[$r->getUserId()] = 1;
            }
            $userIds = array_keys($userIds);


            if(count($userIds)){



                foreach ($userIds as $id) {

                    $user = Mage::getModel('admin/user')->load($id);
                    $name = $user->getName();



                    //Get all report by USER
                    $sreport = Mage::getModel('bs_report/report')->getCollection();
                    if($type == 'supervisor'){
                        $sreport->addFieldToFilter('supervisor_id', Mage::getSingleton('admin/session')->getUser()->getId());
                    }
                    if(count($requestData)){
                        $to = $requestData['to'];
                        $from = $requestData['from'];
                        if($from != ''){
                            $sreport->addFieldToFilter('created_at', array('from'=>$from));
                        }
                        if($to != ''){
                            $sreport->addFieldToFilter('created_at', array('to'=>$to));
                        }

                    }
                    $sreport->addFieldToFilter('user_id', $id)->setOrder('entity_id');
                    if($sreport->count()){
                        $data = array();
                        $i=1;
                        $totalRate = 0;
                        foreach ($sreport as $s) {

                            $supervisor = Mage::getModel('admin/user')->load($s->getSupervisorId());

                            $rate1 = (int)Mage::getSingleton('bs_report/report_attribute_source_rateone')->getOptionText($s->getRateOne());
                            $rate2 = (int)Mage::getSingleton('bs_report/report_attribute_source_rateone')->getOptionText($s->getRateTwo());
                            $rate3 = (int)Mage::getSingleton('bs_report/report_attribute_source_rateone')->getOptionText($s->getRateThree());

                            $rate = ($rate1 + $rate2 + $rate3)/3;
                            $totalRate += $rate;

                            $i++;
                        }

                        $result[] = array(
                            'user'  => $name,
                            'rate'  => round($totalRate/$i, 2)
                        );

                    }
                }

            }
        }

        return array(
            'duration' => $duration,
            'data' => $result
        );
    }

    public function prepareReport($data){
        $wordML = '';
        $i=1;
        $wordML .= '<w:tbl>
                        <w:tblPr>
                            <w:tblpPr w:leftFromText="180" w:rightFromText="180" w:vertAnchor="text"
                                      w:horzAnchor="margin" w:tblpY="144"/>
                            <w:tblW w:w="5000" w:type="pct"/>
                            <w:tblBorders>
                                <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                            </w:tblBorders>
                            <w:tblLook w:val="01E0" w:firstRow="1" w:lastRow="1" w:firstColumn="1" w:lastColumn="1"
                                       w:noHBand="0" w:noVBand="0"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="703"/>
                            <w:gridCol w:w="2551"/>
                            <w:gridCol w:w="1703"/>
                            <w:gridCol w:w="5239"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="00360703" w:rsidRPr="00580384" w:rsidTr="00360703">
                            <w:trPr>
                                <w:trHeight w:val="557"/>
                                <w:tblHeader/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="345" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00360703" w:rsidRPr="00580384" w:rsidRDefault="00360703"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>STT</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1251" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00360703" w:rsidRPr="00580384" w:rsidRDefault="00360703"
                                     w:rsidP="00360703">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Nhân viên</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="835" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00360703" w:rsidRPr="00580384" w:rsidRDefault="00360703"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Mức đánh giá</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="2569" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00360703" w:rsidRPr="00912BF1" w:rsidRDefault="00360703"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Ghi chú</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';
        foreach ($data as $item) {

                $wordML .= '<w:tr w:rsidR="00360703" w:rsidRPr="00580384" w:rsidTr="00360703">
                            <w:trPr>
                                <w:trHeight w:val="376"/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="345" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00360703" w:rsidRPr="004B3289" w:rsidRDefault="00360703"
                                     w:rsidP="00360703">
                                    <w:pPr>
                                        <w:spacing w:before="120" w:after="120"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$i.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1251" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00360703" w:rsidRPr="00580384" w:rsidRDefault="00360703"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="120" w:after="120"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$item['user'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="835" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00360703" w:rsidRPr="00580384" w:rsidRDefault="00360703"
                                     w:rsidP="00360703">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$item['rate'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="2569" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00360703" w:rsidRPr="00580384" w:rsidRDefault="00360703"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="120" w:after="120"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t></w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';

            $i++;
        }
        $wordML .= '</w:tbl>';

        return $wordML;
    }
}
