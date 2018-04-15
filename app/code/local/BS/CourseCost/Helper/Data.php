<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * CourseCost default helper
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Helper_Data extends Mage_Core_Helper_Abstract
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

    public function generateCost($course)
    {
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('coursecost');

        $name = $course->getSku() . '_GIAI TRINH CHI PHI';

        $title = $course->getCourseNameVi();

        if($title == ''){
            $title = $course->getName();
        }

        $currentUser = Mage::getSingleton('admin/session')->getUser();

        $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFirstname() . ' ' . $currentUser->getLastname());

        $today = Mage::getModel('core/date')->date("d/m/Y", time());

        $day = Mage::getModel('core/date')->date("d", time());
        $month = Mage::getModel('core/date')->date("m", time());
        $year = Mage::getModel('core/date')->date("Y", time());

        //Check if this course require to hire instructor from outside VAECO
        //The cost per hour is 120.000 /h
        $isInsOutside = $course->getInsCostOutside();

        $curriculum = false;
        $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($course->getId());

        if ($cu = $curriculums->getFirstItem()) {

            $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($cu->getId());

            $isVietnamese = $curriculum->getCVietnamese();


            $isApprovedCourse = false;

            $compliance = $curriculum->getAttributeText('c_compliance_with');
            $compliance = explode(",", $compliance);
            $compliance = array_map('trim', $compliance);

            if (in_array('MTOE', $compliance)) {
                $isApprovedCourse = true;
            }
            if (in_array('AMOTP', $compliance)) {
                $isApprovedCourse = true;
            }
            if (in_array('RSTP', $compliance)) {
                $isApprovedCourse = true;
            }
            $startDate = $course->getCourseStartDate();
            $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

            $finishDate = $course->getCourseFinishDate();
            $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);

            if ($finishDate == $startDate) {
                $duration = 'Ngày ' .$startDate;

            } else {
                $duration = 'Từ ngày ' . $startDate . ' đến ngày ' . $finishDate;
            }
        }
        $schedules = Mage::getModel('bs_register/schedule')
            ->getCollection()->addFieldToFilter('course_id', $course->getId())
            ->setOrder('entity_id', 'ASC')->setOrder('schedule_order', 'ASC');
        $subjectData = array();
        $totalHour = 0;


        if ($schedules->count()) {

            foreach ($schedules as $sche) {

                $scheHours = $sche->getScheduleHours();
                $totalHour += $scheHours;

            }

        }

        $costData = array();

        $k = 1;
        $tableIns = array();

        $noteG = '';
        $noteIncrement = 1;
        $noteEnglish = array();

        if($isInsOutside){
            $icost = 120000;
            $instructorCost = $totalHour * $icost;

            $tableIns[] = array(
                'i' => '1.1',
                'item' => 'Bồi dưỡng giáo viên (sử dụng giáo viên thuê ngoài được Cục HKVN phê chuẩn)',
                'iunit' => 'Giờ',
                'iqty' => $totalHour,
                'icost' => '120.000',
                'itotal' => number_format($instructorCost,0,',','.'),
                'inote' => '',

            );

            $costData[] = array(
                'cost_desc' => 'Instructors cost ('.$noteIncrement.')',
                'cost' => number_format($instructorCost,0,',','.'),
                'cost_remark' => '400'
            );
            $noteIncrement++;
            $noteEnglish[] = array(
                'ni'    => $noteIncrement,
                'nitem' => 'Detail of Instructor cost: Hiring instructor from external sources: 120.000 VND/hour x '.$totalHour.' hours',
            );

            //make sure this will not included in total
            $instructorCost = 0;

            $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__('(1): Số tiền này được trích từ KHNS năm %s, mục 6.6: Chi phí đào tạo trong nước, mã KMNS: 400', $year));

        }else {
            $icost = 65000;

            if ($isApprovedCourse) {
                $icost = 90000;

            }

            $instructorCost = $totalHour * $icost;

            $tableIns[] = array(
                'i' => '1.1',
                'item' => 'Bồi dưỡng giáo viên',
                'iunit' => 'Giờ',
                'iqty' => $totalHour,
                'icost' => number_format($icost,0,',','.'),
                'itotal' => number_format($instructorCost,0,',','.'),
                'inote' => '',

            );

            $costData[] = array(
                'cost_desc' => 'Instructors cost',
                'cost' => number_format($instructorCost,0,',','.'),
                'cost_remark' => '400'
            );

            $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__('(1): Số tiền này được trích từ KHNS năm %s, mục 6.6: Chi phí đào tạo trong nước, mã KMNS: 400', $year));



        }

        if($course->getInsCostStatement() != ''){
            $tableIns[] = array(
                'i' => '1.2',
                'item' => 'Bồi dưỡng giáo viên được trích tại Tờ trình.',
                'iunit' => '',
                'iqty' => '',
                'icost' => '',
                'itotal' => '',
                'inote' => '(2)',

            );

            $date = $course->getInsCostStatementDate();
            $dayS = Mage::getModel('core/date')->date("d", $date);
            $monthS = Mage::getModel('core/date')->date("m", $date);
            $yearS = Mage::getModel('core/date')->date("Y", $date);

            $costData[] = array(
                'cost_desc' => 'Instructors cost',
                'cost' => '',
                'cost_remark' => 'Statement: '.$course->getInsCostStatement().' ('.$dayS.'/'.$monthS.'/'.$yearS.')'
            );




            $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__('(2): Theo phê duyệt của TGĐ tại Tờ trình %s/TTr-CTKT-TTĐT, ngày %s tháng %s năm %s', $course->getInsCostStatement(),$dayS,$monthS,$yearS));
            $k = 2;
        }


        //$cost = Mage::getModel('bs_coursecost/coursecost')->getCollection()->addFieldToFilter('course_id', $course->getId())->setOrder('costgroup_id', 'ASC');

        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');
        //get all group in this course
        $groupIds = $readConnection->fetchCol("SELECT DISTINCT costgroup_id FROM bs_coursecost_coursecost WHERE course_id=".$course->getId()." ORDER BY costgroup_id ASC");

        $tableE = array();
        $tableP = array();
        $tableT = array();

        $pTotal = 0;
        $eTotal = 0;
        $tTotal = 0;
        $l = 0;
        $m = 0;

        $customCost = 0;

        if(count($groupIds)){
            $i = 1;
            $ii = 1;
            $iii = 1;
            $n = $k;
            $k = 0;
            foreach ($groupIds as $groupId) {
                $group = Mage::getModel('bs_coursecost/costgroup')->load($groupId);

                if($groupId == 6){//practical cost



                    $cost = Mage::getModel('bs_coursecost/coursecost')->getCollection()
                        ->addFieldToFilter('costgroup_id', $groupId)
                        ->addFieldToFilter('course_id', $course->getId())->setOrder('coursecost_order', 'ASC');

                    if($cost->count()){
                        $j = 1;
                        $k = $n;

                        foreach ($cost as $item) {
                            $itemModel = Mage::getModel('bs_coursecost/costitem')->load($item->getCostitemId());
                            $itemCost = (float)$itemModel->getItemCost();
                            $qty = (float)$item->getQty();
                            $note = $item->getNote();

                            //check to see if the note is too long > 6 words?
                            $check = explode(" ", $note);
                            if(count($check) > 6){
                                $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__('('.$n.'): '.$note));

                                $note = '('.$n.')';
                                $n++;
                            }

                            $unit = $itemModel->getItemUnit();


                            $total = (float)$item->getCoursecostCost();

                            $customCost += $total;

                            $pTotal += $total;

                            $itemCost = $itemCost * 1000;

                            if($itemCost == 0){
                                $itemCost = $total / $qty;
                            }



                            $tableP[] = array(
                                'p' => '2.'.$j,
                                'ptem' => $itemModel->getItemName(),
                                'punit' => $unit,
                                'pqty' => $qty,
                                'pcost' => number_format($itemCost,0,',','.'),
                                'ptotal' => number_format($total,0,',','.'),
                                'pnote' => $note,

                            );
                            $j++;

                        }

                        $n++;
                    }

                    $ii++;
                }else if($groupId == 8){ // tickets cost

                    $m = 0;
                    $cost = Mage::getModel('bs_coursecost/coursecost')->getCollection()
                        ->addFieldToFilter('costgroup_id', $groupId)
                        ->addFieldToFilter('course_id', $course->getId())->setOrder('coursecost_order', 'ASC');

                    if($cost->count()){
                        $j = 1;
                        $m = $n;
                        foreach ($cost as $item) {
                            $itemModel = Mage::getModel('bs_coursecost/costitem')->load($item->getCostitemId());
                            $itemCost = (float)$itemModel->getItemCost();
                            $qty = (float)$item->getQty();
                            $note = $item->getNote();

                            //check to see if the note is too long > 6 words?
                            $check = explode(" ", $note);
                            if(count($check) > 6){
                                $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__('('.$n.'): '.$note));

                                $note = '('.$n.')';
                                $n++;
                            }

                            $unit = $itemModel->getItemUnit();

                            $itemCost = $itemCost * 1000;

                            $total = (float)$item->getCoursecostCost();

                            $tTotal += $total;

                            $customCost += $total;

                            if($itemCost == 0){
                                $itemCost = $total / $qty;
                            }




                            $tableT[] = array(
                                't' => '4.'.$j,
                                'ttem' => $itemModel->getItemName(),
                                'tunit' => $unit,
                                'tqty' => $qty,
                                'tcost' => number_format($itemCost,0,',','.'),
                                'ttotal' => number_format($total,0,',','.'),
                                'tnote' => $note,

                            );
                            $j++;

                        }

                        $n++;
                    }

                    $iii++;
                }else {
                    $tableE[] = array(
                        'e' => '3.'.$i,
                        'etem' => Mage::helper('bs_traininglist')->toUpperCase($group->getGroupName()),
                        'eunit' => '',
                        'eqty' => '',
                        'ecost' => '',
                        'etotal' => '',
                        'enote' => '',

                    );

                    $l = 0;

                    $cost = Mage::getModel('bs_coursecost/coursecost')->getCollection()
                        ->addFieldToFilter('costgroup_id', $groupId)
                        ->addFieldToFilter('course_id', $course->getId())->setOrder('coursecost_order', 'ASC');

                    if($cost->count()){
                        $j = 1;
                        $l = $n;
                        foreach ($cost as $item) {
                            $itemModel = Mage::getModel('bs_coursecost/costitem')->load($item->getCostitemId());
                            $itemCost = (float)$itemModel->getItemCost();
                            $qty = (float)$item->getQty();
                            $note = $item->getNote();

                            //check to see if the note is too long > 6 words?
                            $check = explode(" ", $note);
                            if(count($check) > 6){
                                $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__('('.$n.'): '.$note));

                                $note = '('.$n.')';
                                $n++;
                            }

                            $unit = $itemModel->getItemUnit();

                            $itemCost = $itemCost * 1000;
                            $total = (float)$item->getCoursecostCost();

                            $eTotal += $total;

                            $customCost += $total;

                            if($itemCost == 0){
                                $itemCost = $total / $qty;
                            }


                            $tableE[] = array(
                                'e' => '3.'.$i.'.'.$j,
                                'etem' => $itemModel->getItemName(),
                                'eunit' => $unit,
                                'eqty' => $qty,
                                'ecost' => number_format($itemCost,0,',','.'),
                                'etotal' => number_format($total,0,',','.'),
                                'enote' => $note,

                            );
                            $j++;

                        }
                    }
                    $n++;

                    $i++;
                }



            }
        }



        $totalCost = $instructorCost + $customCost;

        if($k > 0){
            $k = '('.$k.')';
            $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__($k.': Số tiền này được trích từ KHNS năm %s, mục 6.6: Chi phí đào tạo trong nước, mã KMNS: 400', $year));
        }else {
            $k = '';
        }


        if($l > 0){
            $l = '('.$l.')';
            $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__($l.': Số tiền được trích từ KHNS năm %s, mục 6.6: Vật tư phục vụ đào tạo, mã KMNS: 402', $year));
        }else {
            $l = '';
        }

        if($m > 0){
            $m = '('.$m.')';
            $noteG .= $this->getXMLNote(Mage::helper('bs_coursecost')->__($m.': Số tiền được trích từ KHNS năm %s, mục 6.6: Vé và công tác phí phục vụ đào tạo trong nước, mã KMNS: 403', $year));
        }else {
            $m = '';
        }


        $data = array(
            'title' => $title,
            'code' => $course->getSku(),
            'duration'  => $duration,
            'hour'  => $totalHour,
            'k'     => $k,
            'l'     => $l,
            'm'     => $m,
            'total'     => number_format($totalCost,0,',','.'),
            'day' => $day,
            'month'      => $month,
            'year' => $year,
            'prepared_by'   => $preparedBy
        );


        if($pTotal > 0){
            $pTotal = number_format($pTotal,0,',','.');
            $costData[] = array(
                'cost_desc' => 'Practical consumable material cost ('.$noteIncrement.')',
                'cost' => $pTotal,
                'cost_remark' => '400'
            );
            $noteEnglish[] = array(
                'ni'    => $noteIncrement,
                'nitem' => 'Detail of Practical consumable material cost: Using available spare parts at '.$totalHour,
            );
            $noteIncrement++;
        }else {
            $pTotal = 'N/A';
            $costData[] = array(
                'cost_desc' => 'Practical consumable material cost',
                'cost' => 'N/A',
                'cost_remark' => ''
            );

            $dataP = array(
                'p'  => '',
                'ptem'     => '',
                'punit'     => '',
                'pqty'     => '',
                'pcost'     => '',
                'ptotal'     => '',
                'pnote'     => '',

            );

            $data = array_merge($data, $dataP);
        }
        if($eTotal > 0){
            $quarter = $this->getQuarter($course->getCourseStartDate());
            $place = $course->getConductingPlace();
            if($place == 208){//HCM
                $location = 'Receive at HCM Branch';
            }else {
                $location = 'Refer to "Statistics of '.$quarter.' quarter '.$year.' for stationary and supplies"';
            }

            $eTotal = number_format($eTotal,0,',','.');
            $costData[] = array(
                'cost_desc' => 'Course stationary and supplies cost ('.$noteIncrement.')',
                'cost' => $eTotal,
                'cost_remark' => '402'
            );
            $noteEnglish[] = array(
                'ni'    => $noteIncrement,
                'nitem' => 'Detail of Course stationary and supplies cost: '.$location,
            );
            $noteIncrement++;
        }else {
            $eTotal = 'N/A';
            $costData[] = array(
                'cost_desc' => 'Course stationary and supplies cost',
                'cost' => 'N/A',
                'cost_remark' => ''
            );
        }
        if($tTotal > 0){
            $tTotal = number_format($tTotal,0,',','.');

            $place = $course->getConductingPlace();
            if($place == 208){//HCM
                $location = 'Receive at HCM Branch';
            }else {
                $location = 'Receive at Administration & Corporate Affairs';
            }

            $costData[] = array(
                'cost_desc' => 'Ticket and working charge ('.$noteIncrement.')',
                'cost' => $tTotal,
                'cost_remark' => '403'
            );
            $noteEnglish[] = array(
                'ni'    => $noteIncrement,
                'nitem' => 'Detail of Ticket and working charge: '.$location,
            );

        }else {
            $tTotal = 'N/A';
            $dataT = array(
                't'  => '',
                'ttem'     => '',
                'tunit'     => '',
                'tqty'     => '',
                'tcost'     => '',
                'ttotal'     => '',
                'tnote'     => '',

            );
            $costData[] = array(
                'cost_desc' => 'Ticket and working charge',
                'cost' => 'N/A',
                'cost_remark' => ''
            );

            $data = array_merge($data, $dataT);
        }


        $dataTotal = array(
            'i_total'  => number_format($instructorCost,0,',','.'),
            'p_total'     => $pTotal,
            'e_total'     => $eTotal,
            't_total'     => $tTotal,

        );

        $data = array_merge($data, $dataTotal);

        $tableData = array($tableIns, $tableE, $tableP, $tableT);

        $tableHtml = array(
            'variable'  => 'NOTE',
            'content'   => $noteG
        );


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, $tableData,null,null,null,null,null,$tableHtml);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );
            
            //we need to return the costs for 8007
            $result = array();
            $result['year'] = $year;
            $result['cost_data']    = $costData;
            $result['total']    = number_format($totalCost,0,',','.');
            $result['note'] = $this->buildXMLNote($noteEnglish);


            return $result;
            


        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    public function getXMLNote($str){
        $str = str_replace("&", "&amp;", $str);
        return '<w:p w:rsidR="00AA41D3" w:rsidRPr="00457C69" w:rsidRDefault="00457C69">
                        <w:pPr>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:i/>
                                <w:sz w:val="20"/>
                                <w:szCs w:val="20"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r w:rsidRPr="00457C69">
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:i/>
                                <w:sz w:val="20"/>
                                <w:szCs w:val="20"/>
                            </w:rPr>
                            <w:t xml:space="preserve">'.$str.'</w:t>
                        </w:r>
                       
                    </w:p>';
    }

    public function getQuarter($date){
        $month = (int)Mage::getModel('core/date')->date("m", $date);
        if($month < 4){
            return '1st';
        }elseif ($month < 7){
            return '2nd';
        }elseif ($month < 10){
            return '3rd';
        }else {
            return '4th';
        }
    }

    public function buildXMLNote($data){
        if(count($data)){
            $xml = '<w:p w:rsidR="00186C4F" w:rsidRDefault="00186C4F" w:rsidP="00186C4F">
                        <w:pPr>
                            <w:spacing w:before="120" w:after="60"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                                <w:sz w:val="20"/>
                                <w:szCs w:val="20"/>
                                <w:u w:val="single"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r w:rsidRPr="006868C1">
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                                <w:sz w:val="20"/>
                                <w:szCs w:val="20"/>
                                <w:u w:val="single"/>
                            </w:rPr>
                            <w:t>Note:</w:t>
                        </w:r>
                        <w:bookmarkStart w:id="0" w:name="_GoBack"/>
                        <w:bookmarkEnd w:id="0"/>
                    </w:p>';

            $i=1;
            foreach ($data as $item) {
                $xml .= '<w:tbl>
                        <w:tblPr>
                            <w:tblStyle w:val="TableGrid"/>
                            <w:tblpPr w:leftFromText="180" w:rightFromText="180" w:vertAnchor="text" w:tblpY="1"/>
                            <w:tblOverlap w:val="never"/>
                            <w:tblW w:w="5000" w:type="pct"/>
                            <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0"
                                       w:noHBand="0" w:noVBand="1"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="661"/>
                            <w:gridCol w:w="9535"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="00186C4F" w:rsidTr="00833CB5">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="324" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00186C4F" w:rsidRPr="00885D1F" w:rsidRDefault="00186C4F"
                                     w:rsidP="007C6239">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00885D1F">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$item['ni'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="4676" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00186C4F" w:rsidRPr="00885D1F" w:rsidRDefault="00186C4F"
                                     w:rsidP="007C6239">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00885D1F">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$item['nitem'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>
                    </w:tbl>';
            }

            return $xml;
        }

        return '';
    }
}
