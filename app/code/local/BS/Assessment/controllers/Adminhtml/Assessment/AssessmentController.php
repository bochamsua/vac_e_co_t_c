<?php
/**
 * BS_Assessment extension
 * 
 * @category       BS
 * @package        BS_Assessment
 * @copyright      Copyright (c) 2015
 */
/**
 * Assessment admin controller
 *
 * @category    BS
 * @package     BS_Assessment
 * @author Bui Phong
 */
class BS_Assessment_Adminhtml_Assessment_AssessmentController extends BS_Assessment_Controller_Adminhtml_Assessment
{
    /**
     * init the assessment
     *
     * @access protected
     * @return BS_Assessment_Model_Assessment
     */
    protected function _initAssessment()
    {
        $assessmentId  = (int) $this->getRequest()->getParam('id');
        $assessment    = Mage::getModel('bs_assessment/assessment');
        if ($assessmentId) {
            $assessment->load($assessmentId);
        }
        Mage::register('current_assessment', $assessment);
        return $assessment;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('bs_assessment')->__('Practical Assessment'))
             ->_title(Mage::helper('bs_assessment')->__('Assessments'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit assessment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $assessmentId    = $this->getRequest()->getParam('id');
        $assessment      = $this->_initAssessment();
        if ($assessmentId && !$assessment->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_assessment')->__('This assessment no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getAssessmentData(true);
        if (!empty($data)) {
            $assessment->setData($data);
        }
        Mage::register('assessment_data', $assessment);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_assessment')->__('Practical Assessment'))
             ->_title(Mage::helper('bs_assessment')->__('Assessments'));
        if ($assessment->getId()) {
            $this->_title($assessment->getContent());
        } else {
            $this->_title(Mage::helper('bs_assessment')->__('Add assessment'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new assessment action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save assessment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('assessment')) {
            try {
                $data = $this->_filterDates($data, array('prepared_date'));
                $assessment = $this->_initAssessment();
                $assessment->addData($data);
                $assessment->save();

                //Generate 2039
                $template = Mage::helper('bs_formtemplate')->getFormtemplate('2039');
                $templateTinnhan = Mage::helper('bs_formtemplate')->getFormtemplate('tinnhan');

                $productId = $data['course_id'];
                $product = Mage::getModel('catalog/product')->load($productId);
                $sku = $product->getSku();

                $nameVi = $product->getCourseRequestedName();
                if($nameVi == ''){
                    $nameVi = $product->getName();
                }

                $guitoi = 'Nguyễn Bá Việt';
                $donvi = 'Tổ tiêu chuẩn - Phòng Giáo vụ - TTĐT';
                $sdt = '0983631683';
                $conductingPlace = $product->getConductingPlace();//209: HAN, 208: HCM

                $locationdept = 'HAN LINE MAINTENANCE CENTER';

                if($conductingPlace == 208){
                    $guitoi = 'Lê Thăng Long';
                    $donvi = 'Phòng Đào tạo phía Nam - TTĐT';
                    $sdt = '0908320058';

                    $locationdept = 'HCM LINE MAINTENANCE CENTER';
                }


                $content = $data['content'];
                $hoanthanh = $content;

                if(strpos("moke".strtolower($content),"block 1")){
                    $hoanthanh = $hoanthanh.' (A.1-A.10)';
                }elseif(strpos("moke".strtolower($content),"block 2")){
                    $hoanthanh = $hoanthanh.' (A.11-A.18)';
                }



                $contentarray = explode(" ", $content);
                $ref = $sku.'-VIEW';
                if($data['suffix'] != ''){
                    $ref .= '-'.$data['suffix'];
                }

                /*for($i=0; $i< count($contentarray); $i++){
                    $ref .= $contentarray[$i][0];
                }
                $ref .= '-VIEW';*/



                $detail = $data['detail'];
                $duration = floatval($data['duration']);
                $article = Mage::getSingleton('bs_assessment/assessment_attribute_source_article')->getOptionText($data['article']);
                $appType = Mage::getSingleton('bs_assessment/assessment_attribute_source_apptype')->getOptionText($data['app_type']);
                $appCat = Mage::getSingleton('bs_assessment/assessment_attribute_source_appcat')->getOptionText($data['app_cat']);


                if($appCat != 'A'){
                    $locationdept = 'HAN BASE MAINTENANCE CENTER';

                    if($conductingPlace == 208){
                        $locationdept = 'HCM BASE MAINTENANCE CENTER';
                    }
                }


                $date = $data['prepared_date'];
                $date = Mage::getModel('core/date')->date("d/m/Y", $date);



                $dept = array();
                $dates = array();
                $instructors = array();
                $trainees = array();
                $contents = explode("\r\n", $detail);
                $tableData = array();
                $rowsWordMl = '';
                $total = 0;
                $thoigian = '';
                $han = '';
                $loptruong  = '';
                $lanhdao = array();
                $note = '*Lưu ý: các học viên này đều chưa có Giấy phép bảo dưỡng tàu bay (Aircraft Maintenance Licence) do CAAV cấp.';

                if(count($contents)){

                    $j=1;
                    foreach ($contents as $item) {

                        $items = explode("--", $item);

                        $sheduleDate = '';
                        $traineeID = '';
                        $instructorId = '';
                        $time = '';

                        if(count($items) == 4){//we have time
                            $sheduleDate = trim($items[0]);
                            $traineeID = trim($items[1]);
                            $instructorId = trim($items[2]);
                            $time = trim($items[3]);


                        }elseif (count($items) == 3){
                            $sheduleDate = trim($items[0]);
                            $traineeID = trim($items[1]);
                            $instructorId = trim($items[2]);
                        }

                        if($sheduleDate != ''){
                            $dates[$sheduleDate] = 1;
                        }

                        if($traineeID != '' && $instructorId != ''){


                            if(strlen($traineeID) == 5){
                                $traineeID = "VAE".$traineeID;
                            }elseif (strlen($traineeID) == 4){
                                $traineeID = "VAE0".$traineeID;
                            }
                            $traineeID = strtoupper($traineeID);




                            if(strlen($instructorId) == 5){
                                $instructorId = "VAE".$instructorId;
                            }elseif (strlen($instructorId) == 4){
                                $instructorId = "VAE0".$instructorId;
                            }
                            $instructorId = strtoupper($instructorId);

                            $traineeName = '';
                            $traineeDept = '';
                            $traineeVaecoid = '';
                            $traineeUsername = '';
                            $instructorName = '';
                            $instructorUsername = '';
                            $shortName = '';

                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $traineeID)->getFirstItem();
                            if($customer->getId()){
                                $trainee = Mage::getModel('customer/customer')->load($customer->getId());

                                $dept[$trainee->getGroupId()] = 1;

                                $group = Mage::getModel('customer/group')->load($trainee->getGroupId());
                                $traineeDept = $group->getCustomerGroupCode();

                                $trainees[$trainee->getId()] = 1;
                                $traineeUsername = $trainee->getUsername();
                                $traineeName = $trainee->getName();
                            }

                            //check CAAV Certificate
                            $cert = Mage::getModel('bs_certificate/certificate')->getCollection()->addFieldToFilter('vaeco_id', $traineeID)->getFirstItem();//->addFieldToFilter('apply_for');
                            $add = '';
                            if($cert->getId()){
                                $certNo = $cert->getCertNo();
                                $issueDate = DateTime::createFromFormat('Y-m-d H:i:s', $cert->getIssueDate())->format('d/m/Y');
                                $expireDate = DateTime::createFromFormat('Y-m-d H:i:s', $cert->getExpireDate())->format('d/m/Y');



                                $add .= 'Học viên '.$traineeName.' đã có Giấy phép bảo dưỡng tàu bay (Aircraft Maintenance Licence) do CAAV cấp - Số '. $certNo.', ngày cấp '.$issueDate.', ngày hết hạn '.$expireDate.'; ';

                            }

                            if($add != ''){
                                $note = '*Lưu ý: Trong đó có: '.$add.'Còn lại các học viên khác đều chưa có Giấy phép bảo dưỡng tàu bay (Aircraft Maintenance Licence) do CAAV cấp';


                            }

                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $instructorId)->getFirstItem();
                            if($customer->getId()){
                                $instructor = Mage::getModel('customer/customer')->load($customer->getId());
                                $dept[$instructor->getGroupId()] = 1;
                                $instructors[$instructor->getId()] = 1;
                                $instructorName = $instructor->getName();
                                $instructorUsername = $instructor->getUsername();

                                $nameArray = explode(" ", $instructorName);
                                $last = $nameArray[count($nameArray)-1];

                                for($i=0; $i< count($nameArray)-1; $i++){
                                    $shortName .= $nameArray[$i][0].'.';
                                }
                                $shortName .= $last;
                            }

                            $timeframe = '8h30÷16h30';
                            $time = (float)$time;
                            if($time <= 3.5 && $time > 0){
                                $timeframe = '8h30÷12h00';
                            }elseif($duration <= 3.5) {
                                    $timeframe = '8h30÷12h00';
                            }
                            $tableData[] = array(
                                'date'  => $sheduleDate,
                                'no'    => $j,
                                'vaeco_id' => $traineeID,
                                'name'  => $traineeName,
                                'dept'  => $traineeDept,
                                'article'   => $article,
                                'app_type'  => $appType,
                                'app_cat'   => $appCat,
                                'detail'    => $content,
                                'time'      => $timeframe,
                                'instructor'    => $shortName


                            );


                            $j++;

                        }




                    }

                }
                $sentto = '';

                if(count($dept)){
                    $dept = array_keys($dept);
                    foreach ($dept as $id) {
                        if($id == 7 || $id == 23){//7: QA, 23: TC
                            continue;
                        }
                        $group = Mage::getModel('customer/group')->load($id);
                        $sentto .= strtoupper($group->getCustomerGroupCode()).' DIRECTOR;';

                        //find director
                        $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('group_id', $id)->addAttributeToFilter('position', array('like'=>'%Giám đốc%'));
                        if($cus->count()){
                            foreach ($cus as $cusId) {
                                $director = Mage::getModel('customer/customer')->load($cusId->getId());
                                $lanhdao[] = $director->getUsername();
                            }

                        }
                    }

                }


                $listvars = array();
                $listinstructor = array();
                $instructors = array_keys($instructors);
                $instructorStr = array();
                if(count($instructors)){
                    foreach ($instructors as $id) {
                        $ins = Mage::getModel('customer/customer')->load($id);
                        $instructorStr[] = $ins->getUsername();

                        $listinstructor[] = mb_strtoupper($ins->getName(), 'UTF-8');
                    }

                }
                $instructorStr = implode(", ", $instructorStr);

                //trainee
                $trainees = array_keys($trainees);
                $traineeStr = array();
                if(count($trainees)){
                    $monitorId = $trainees[0];
                    $monitor = Mage::getModel('customer/customer')->load($monitorId);
                    $loptruong = $monitor->getName();

                    foreach ($trainees as $id) {
                        $tn = Mage::getModel('customer/customer')->load($id);
                        $traineeStr[] = $tn->getUsername();
                    }

                }
                $traineeStr = implode(", ", $traineeStr);





                $listvars['listinstructors'] = $listinstructor;

                if(count($dates)){
                    $year = date("Y", Mage::getModel('core/date')->timestamp(time()));

                    $dates = array_keys($dates);
                    $newDates = array();
                    foreach ($dates as $day) {
                        $day = $day.'/'.$year;
                        $dateFull = DateTime::createFromFormat('d/m/Y', $day);
                        $newDates[] = $dateFull;

                    }

                    sort($newDates);

                    if(count($newDates) > 1){
                        $from = $newDates[0];
                        $to = $newDates[count($newDates)-1];
                        $han = $to;
                        $from = $from->format('d/m');
                        $to = $to->format('d/m/Y');

                        $thoigian = $from.' - '.$to;


                        $han = $han->add(new DateInterval('P3D'));
                        $number = $han->format('N');

                        if($number == 6){
                            $han = $han->add(new DateInterval('P2D'));
                        }elseif ($number == 7){
                            $han = $han->add(new DateInterval('P1D'));
                        }

                        $han = $han->format('d/m/Y');


                    }else {
                        $from = $newDates[0];
                        $han = $from;
                        $from = $from->format('d/m/Y');
                        $thoigian = $from;


                        $han = $han->add(new DateInterval('P3D'));
                        $number = date('N', strtotime($han));

                        if($number == 6){
                            $han = $han->add(new DateInterval('P2D'));
                        }elseif ($number == 7){
                            $han = $han->add(new DateInterval('P1D'));
                        }

                        $han = $han->format('d/m/Y');

                    }



                }

                $lanhdao = implode(", ", $lanhdao);

                if($conductingPlace == 208){
                    $instructorStr = 'nguyentruongson, lethanglong, '.$instructorStr;
                }

                $tableML = array('isarray'=>true);
                if(count($tableData)){
                    $tableML['array']['table'] = $this->prepareRowsML($tableData);
                }

                $tableML['array']['remark'] = $this->prepareRemark($content, $appCat);




                $templateData = array(
                    'sendto'  => $sentto,
                    'ref'    => $this->getFormattedText($ref),
                    'date'   => $date,
                    'locationdept' => $locationdept,
                    'tenkhoa'   => $nameVi,
                    'code'  => $sku,
                    'hoanthanh' => $hoanthanh,
                    'guitoi'    => $guitoi,
                    'donvi'     => $donvi,
                    'sdt'       => $sdt,
                    'thoigian'  => $thoigian,
                    'han'       => $han,
                    'loptruong' => $loptruong,
                    'giaovien'  => $instructorStr,
                    'hocvien'   => $traineeStr,
                    'lanhdao'   => $lanhdao,
                    'caav_note' => $note


                );


                try {
                    $res = Mage::helper('bs_traininglist/docx')->generateDocx($sku.'_2039', $template, $templateData,null,null,$listvars,null,null,null,$tableML);


                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                    );

                    $res = Mage::helper('bs_traininglist/docx')->generateDocx($sku.'_TIN NHAN', $templateTinnhan, $templateData);


                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                    );




                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_assessment')->__('Assessment was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $assessment->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setAssessmentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_assessment')->__('There was a problem saving the assessment.')
                );
                Mage::getSingleton('adminhtml/session')->setAssessmentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_assessment')->__('Unable to find assessment to save.')
        );
        $this->_redirect('*/*/');
    }

    public function prepareRemark($content, $appcat = ''){
        $remark = '<w:p w:rsidR="00D7307C" w:rsidRDefault="00D7307C" w:rsidP="00462996">
                        <w:pPr>
                            <w:tabs>
                                <w:tab w:val="left" w:pos="2300"/>
                                <w:tab w:val="left" w:leader="dot" w:pos="5800"/>
                            </w:tabs>
                            <w:spacing w:before="120"/>
                            <w:ind w:left="62"/>
                        </w:pPr>
                        <w:r w:rsidRPr="00307C17">
                            <w:rPr>
                                <w:b/>
                                <w:u w:val="single"/>
                            </w:rPr>
                            <w:t>Remark:</w:t>
                        </w:r>
                        <w:r w:rsidRPr="00307C17">
                            <w:t xml:space="preserve">By copy of this Authorisation assessment schedule, all assesors are invited to join the assessment board. </w:t>
                        </w:r>

                    </w:p>';


        if($appcat == 'A'){
            $remark = '<w:p w:rsidR="00D7307C" w:rsidRDefault="00D7307C" w:rsidP="001D4FFB">
                        <w:pPr>
                            <w:tabs>
                                <w:tab w:val="left" w:pos="2300"/>
                                <w:tab w:val="left" w:leader="dot" w:pos="5800"/>
                            </w:tabs>
                            <w:spacing w:before="120"/>
                            <w:ind w:left="57"/>
                        </w:pPr>
                        <w:r w:rsidRPr="00307C17">
                            <w:rPr>
                                <w:b/>
                                <w:u w:val="single"/>
                            </w:rPr>
                            <w:t>Remark:</w:t>
                        </w:r>
                        <w:r w:rsidR="001D4FFB">
                            <w:t xml:space="preserve">  </w:t>
                        </w:r>
                        <w:r w:rsidRPr="008D674C">
                            <w:rPr>
                                <w:b/>
                            </w:rPr>
                            <w:t>Block 1:</w:t>
                        </w:r>
                        <w:r w:rsidRPr="008D674C">
                            <w:t xml:space="preserve"> Complete the tasks from A.1÷A.10</w:t>
                        </w:r>
                    </w:p>
                    <w:p w:rsidR="00D7307C" w:rsidRDefault="00D7307C" w:rsidP="008D7547">
                        <w:pPr>
                            <w:tabs>
                                <w:tab w:val="left" w:pos="2300"/>
                                <w:tab w:val="left" w:leader="dot" w:pos="5800"/>
                            </w:tabs>
                            <w:spacing w:before="120"/>
                            <w:ind w:left="964"/>
                        </w:pPr>
                        <w:r w:rsidRPr="00347922">
                            <w:rPr>
                                <w:b/>
                            </w:rPr>
                            <w:t>Block 2: </w:t>
                        </w:r>

                        <w:r>
                            <w:t xml:space="preserve"> Complete the tasks from A.11÷A.18</w:t>
                        </w:r>

                    </w:p>
                    <w:p w:rsidR="00D7307C" w:rsidRDefault="00D7307C" w:rsidP="00D7307C">
                        <w:pPr>
                            <w:tabs>
                                <w:tab w:val="left" w:pos="2300"/>
                                <w:tab w:val="left" w:leader="dot" w:pos="5800"/>
                            </w:tabs>
                            <w:spacing w:before="120"/>
                            <w:ind w:left="964"/>
                        </w:pPr>
                        <w:r w:rsidRPr="00307C17">
                            <w:t xml:space="preserve">By copy of this Authorisation assessment schedule, all assesors are invited to join the assessment board.</w:t>
                        </w:r>

                    </w:p>';
        }
        return $remark;
    }

    public function prepareRowsML($data){
        $rowsML = '<w:tbl>
                        <w:tblPr>
                            <w:tblW w:w="5000" w:type="pct"/>
                            <w:tblBorders>
                                <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                            </w:tblBorders>
                            <w:tblCellMar>
                                <w:left w:w="0" w:type="dxa"/>
                                <w:right w:w="0" w:type="dxa"/>
                            </w:tblCellMar>
                            <w:tblLook w:val="01E0" w:firstRow="1" w:lastRow="1" w:firstColumn="1" w:lastColumn="1"
                                       w:noHBand="0" w:noVBand="0"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="703"/>
                            <w:gridCol w:w="401"/>
                            <w:gridCol w:w="1144"/>
                            <w:gridCol w:w="2246"/>
                            <w:gridCol w:w="1264"/>
                            <w:gridCol w:w="1264"/>
                            <w:gridCol w:w="1123"/>
                            <w:gridCol w:w="982"/>
                            <w:gridCol w:w="1576"/>
                            <w:gridCol w:w="1371"/>
                            <w:gridCol w:w="1132"/>
                            <w:gridCol w:w="2093"/>
                        </w:tblGrid>

                    ';
        foreach ($data as $item) {

            $detail = str_replace("&","&amp;",$item['detail']);
            $rowsML .= '<w:tr w:rsidR="0054481F" w:rsidRPr="00307C17" w:rsidTr="00005802">
                            <w:trPr>

                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="230" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:tcMar>
                                        <w:left w:w="57" w:type="dxa"/>
                                        <w:right w:w="57" w:type="dxa"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="00724228" w:rsidRDefault="0054481F"
                                     w:rsidP="0054481F">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:lang w:val="en-US"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:lang w:val="en-US"/>
                                        </w:rPr>
                                        <w:t>'.$item['date'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="131" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="00307C17" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:t>'.$item['no'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="374" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="00CA7B66" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:t>'.$item['vaeco_id'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="734" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="00CA7B66" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="57"/>
                                    </w:pPr>

                                    <w:r>
                                        <w:t>'.$item['name'].'</w:t>
                                    </w:r>

                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="413" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="00F35DB7" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:spacing w:val="-10"/>
                                            <w:lang w:val="pt-PT"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:spacing w:val="-10"/>
                                            <w:lang w:val="pt-PT"/>
                                        </w:rPr>
                                        <w:t>'.$item['dept'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="413" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRDefault="0054481F" w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:t>'.$item['article'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="367" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="001A6765" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:t>'.$item['app_type'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="321" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="001A6765" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                    <w:r w:rsidRPr="001A6765">
                                        <w:t>'.$item['app_cat'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="515" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRDefault="0054481F" w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:t xml:space="preserve">'.$detail.'</w:t>
                                    </w:r>

                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="448" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="00F33386" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="19" w:right="-79"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00F33386">
                                        <w:t>'.$item['time'].'</w:t>
                                    </w:r>

                                </w:p>
                                <w:p w:rsidR="0054481F" w:rsidRPr="002D2A8F" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="19" w:right="-79"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:lang w:val="en-US"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:b/>
                                        </w:rPr>
                                        <w:t>'.$item['instructor'].'</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="370" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="00307C17" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="19" w:right="-79"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:t>N/A</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="684" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054481F" w:rsidRPr="00307C17" w:rsidRDefault="0054481F"
                                     w:rsidP="00005802">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="19" w:right="-79"/>
                                        <w:jc w:val="center"/>
                                    </w:pPr>
                                </w:p>
                            </w:tc>
                        </w:tr>';
        }
        $rowsML .= '</w:tbl>';
        return $rowsML;
    }

    public function getFormattedText($value){

        $text = $value;
        $text = preg_replace('/[^a-z0-9A-Z_\\-\\.]+/i', '-', $text);

        return $text;
    }

    /**
     * delete assessment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $assessment = Mage::getModel('bs_assessment/assessment');
                $assessment->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_assessment')->__('Assessment was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_assessment')->__('There was an error deleting assessment.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_assessment')->__('Could not find assessment to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete assessment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $assessmentIds = $this->getRequest()->getParam('assessment');
        if (!is_array($assessmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_assessment')->__('Please select assessments to delete.')
            );
        } else {
            try {
                foreach ($assessmentIds as $assessmentId) {
                    $assessment = Mage::getModel('bs_assessment/assessment');
                    $assessment->setId($assessmentId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_assessment')->__('Total of %d assessments were successfully deleted.', count($assessmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_assessment')->__('There was an error deleting assessments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massStatusAction()
    {
        $assessmentIds = $this->getRequest()->getParam('assessment');
        if (!is_array($assessmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_assessment')->__('Please select assessments.')
            );
        } else {
            try {
                foreach ($assessmentIds as $assessmentId) {
                $assessment = Mage::getSingleton('bs_assessment/assessment')->load($assessmentId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d assessments were successfully updated.', count($assessmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_assessment')->__('There was an error updating assessments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass On article change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massArticleAction()
    {
        $assessmentIds = $this->getRequest()->getParam('assessment');
        if (!is_array($assessmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_assessment')->__('Please select assessments.')
            );
        } else {
            try {
                foreach ($assessmentIds as $assessmentId) {
                $assessment = Mage::getSingleton('bs_assessment/assessment')->load($assessmentId)
                    ->setArticle($this->getRequest()->getParam('flag_article'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d assessments were successfully updated.', count($assessmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_assessment')->__('There was an error updating assessments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass App. Type change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massAppTypeAction()
    {
        $assessmentIds = $this->getRequest()->getParam('assessment');
        if (!is_array($assessmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_assessment')->__('Please select assessments.')
            );
        } else {
            try {
                foreach ($assessmentIds as $assessmentId) {
                $assessment = Mage::getSingleton('bs_assessment/assessment')->load($assessmentId)
                    ->setAppType($this->getRequest()->getParam('flag_app_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d assessments were successfully updated.', count($assessmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_assessment')->__('There was an error updating assessments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass App Cat change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massAppCatAction()
    {
        $assessmentIds = $this->getRequest()->getParam('assessment');
        if (!is_array($assessmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_assessment')->__('Please select assessments.')
            );
        } else {
            try {
                foreach ($assessmentIds as $assessmentId) {
                $assessment = Mage::getSingleton('bs_assessment/assessment')->load($assessmentId)
                    ->setAppCat($this->getRequest()->getParam('flag_app_cat'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d assessments were successfully updated.', count($assessmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_assessment')->__('There was an error updating assessments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'assessment.csv';
        $content    = $this->getLayout()->createBlock('bs_assessment/adminhtml_assessment_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'assessment.xls';
        $content    = $this->getLayout()->createBlock('bs_assessment/adminhtml_assessment_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'assessment.xml';
        $content    = $this->getLayout()->createBlock('bs_assessment/adminhtml_assessment_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/assessment');
    }
}
