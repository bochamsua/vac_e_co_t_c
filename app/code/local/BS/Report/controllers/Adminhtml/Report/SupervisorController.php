<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Individual Report admin controller
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Adminhtml_Report_SupervisorController extends BS_Report_Controller_Adminhtml_Report
{
    /**
     * init the individual report
     *
     * @access protected
     * @return BS_Report_Model_Report
     */
    protected function _initReport()
    {
        $reportId  = (int) $this->getRequest()->getParam('id');
        $report    = Mage::getModel('bs_report/report');
        if ($reportId) {
            $report->load($reportId);
        }
        Mage::register('current_report', $report);
        return $report;
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
        $this->_title(Mage::helper('bs_report')->__('Report'))
             ->_title(Mage::helper('bs_report')->__('Supervisor View'));
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

    public function reportAction()
    {
        $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));
        $requestData = $this->_filterDates($requestData, array('from', 'to'));

        $result = Mage::helper('bs_report')->getReportStatistic($requestData, 'supervisor');

        if(count($result)){

            $templateData = array(
                'duration' => $result['duration']
            );

            $template = Mage::helper('bs_formtemplate')->getFormtemplate('report');
            $contentHtml = array(
                'type' => 'replace',
                'content' => Mage::helper('bs_report')->prepareReport($result['data']),
                'variable' => 'content'
            );


            $res = Mage::helper('bs_traininglist/docx')->generateDocx('EMPLOYEE REPORT SUPERVISOR', $template, $templateData, null, null,null,null,null,null,$contentHtml);

            $this->_getSession()->addSuccess(Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name']));

        }else {
            $this->_getSession()->addNotice(Mage::helper('bs_traininglist')->__('There is no record found!'));
        }



        $this->_redirect('*/*/');
    }

    public function prepareReport($data){
        $wordML = '';
        $i=1;
        foreach ($data as $item) {

            $wordML .= '<w:p w:rsidR="00990FB4" w:rsidRPr="00990FB4" w:rsidRDefault="00990FB4" w:rsidP="00DE11C0">
                        <w:pPr>
                            <w:spacing w:before="120"/>
                            <w:jc w:val="both"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                            </w:rPr>
                        </w:pPr>
                        <w:proofErr w:type="spellStart"/>
                        <w:r w:rsidRPr="00990FB4">
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                            </w:rPr>
                            <w:t xml:space="preserve">'.$i.'. '.$item['user'].'</w:t>
                        </w:r>
                        <w:proofErr w:type="spellEnd"/>
                    </w:p>';

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
                            <w:gridCol w:w="1758"/>
                            <w:gridCol w:w="2916"/>
                            <w:gridCol w:w="3968"/>
                            <w:gridCol w:w="2267"/>
                            <w:gridCol w:w="1136"/>
                            <w:gridCol w:w="1592"/>
                            <w:gridCol w:w="2059"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidTr="0096380B">
                            <w:trPr>
                                <w:trHeight w:val="557"/>
                                <w:tblHeader/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="560" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
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
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Ngày</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="929" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
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
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Công việc</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1264" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
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
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Chi tiết công việc</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="722" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00912BF1" w:rsidRDefault="0096380B"
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
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Người giao</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="362" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">% Hoàn thành</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="507" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="-144" w:right="-144"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Ngày dự kiến hoàn thành</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="656" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:right="-108"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Ghi chú</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                        </w:tr>';

            foreach ($item['data'] as $row) {

                $detail = $row['detail'];
                $detail = explode("\r\n", $detail);
                $detailML = '';
                foreach ($detail as $li) {
                    $detailML .= '<w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">- '.$li.'</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>';
                }


                $wordML .= '<w:tr w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidTr="0096380B">
                            <w:trPr>
                                <w:trHeight w:val="376"/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="560" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="004B3289" w:rsidRDefault="0096380B"
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
                                        <w:t>'.$row['date'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="929" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="120" w:after="120"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$row['brief'].'</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1264" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                '.$detailML.'
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="722" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
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
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$row['supervisor'].'</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="362" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
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
                                        <w:t>'.$row['percent'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="507" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
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
                                        <w:t>'.$row['ex_date'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="656" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0096380B" w:rsidRPr="00580384" w:rsidRDefault="0096380B"
                                     w:rsidP="0096380B">
                                    <w:pPr>
                                        <w:spacing w:before="120" w:after="120"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:proofErr w:type="spellStart"/>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$row['note'].'</w:t>
                                    </w:r>
                                    <w:proofErr w:type="spellEnd"/>
                                </w:p>
                            </w:tc>
                        </w:tr>';
            }


            $wordML .= '</w:tbl>';
            $i++;
        }

        return $wordML;
    }

    /**
     * edit individual report - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $reportId    = $this->getRequest()->getParam('id');
        $report      = $this->_initReport();
        if ($reportId && !$report->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_report')->__('This individual report no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getReportData(true);
        if (!empty($data)) {
            $report->setData($data);
        }
        Mage::register('report_data', $report);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_report')->__('Report'))
             ->_title(Mage::helper('bs_report')->__('Reports'));
        if ($report->getId()) {
            $this->_title($report->getBrief());
        } else {
            $this->_title(Mage::helper('bs_report')->__('Supervisor report rating'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new individual report action
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
     * save individual report - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        $rateOne= $data = $this->getRequest()->getPost('rate_one');
        $rateTwo= $data = $this->getRequest()->getPost('rate_two');
        $rateThree= $data = $this->getRequest()->getPost('rate_three');
        $rateNote= $data = $this->getRequest()->getPost('note');

        if($rateOne){
            try {
                foreach ($rateOne as $key => $value) {
                    $report = Mage::getModel('bs_report/report')->load($key);
                    $report->setRateOne($value);
                    $report->setRateTwo($rateTwo[$key]);
                    $report->setRateThree($rateThree[$key]);
                    $report->setNote($rateNote[$key]);
                    $report->save();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('The rating was saved.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setReportData($data);
                $this->_redirect('*/*/new');
                return;
            }catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was a problem saving the rating.')
                );
                Mage::getSingleton('adminhtml/session')->setReportData($data);
                $this->_redirect('*/*/new');
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_report')->__('An unknown error occurred.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete individual report - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $report = Mage::getModel('bs_report/report');
                $report->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('Individual Report was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error deleting individual report.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_report')->__('Could not find individual report to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete individual report - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports to delete.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                    $report = Mage::getModel('bs_report/report');
                    $report->setId($reportId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('Total of %d reports were successfully deleted.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error deleting reports.')
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
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                $report = Mage::getSingleton('bs_report/report')->load($reportId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d reports were successfully updated.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating reports.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Complete (%) change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCompleteAction()
    {
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                $report = Mage::getSingleton('bs_report/report')->load($reportId)
                    ->setComplete($this->getRequest()->getParam('flag_complete'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d reports were successfully updated.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating reports.')
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
        $fileName   = 'report-supervisor.csv';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_supervisor_grid')
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
        $fileName   = 'report-supervisor.xls';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_supervisor_grid')
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
        $fileName   = 'report-supervisor.xml';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_supervisor_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_report/report');
    }
}
