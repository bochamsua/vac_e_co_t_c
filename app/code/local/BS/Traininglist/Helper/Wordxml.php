<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Traininglist default helper
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Helper_Wordxml extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Bui Phong
     */

    public function prepareSevenTable($data)
    {

        $wordML = '<w:tbl>
                        <w:tblPr>
                            <w:tblStyle w:val="TableGridPHPDOCX"/>
                            <w:tblW w:w="5000" w:type="pct"/>
                            <w:tblBorders>
                                <w:top w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:bottom w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:insideH w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:insideV w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                            </w:tblBorders>
                            <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0"
                                       w:noHBand="0" w:noVBand="1"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="582"/>
                            <w:gridCol w:w="1418"/>
                            <w:gridCol w:w="3681"/>
                            <w:gridCol w:w="425"/>
                            <w:gridCol w:w="456"/>
                            <w:gridCol w:w="2075"/>
                            <w:gridCol w:w="1551"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="00D461C5" w14:paraId="0870751F" w14:textId="77777777" w:rsidTr="00D7433F">
                            <w:trPr>
                                <w:tblHeader/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="288" w:type="pct"/>

                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="579D3BD3" w14:textId="77777777" w:rsidR="00D461C5"
                                     w:rsidRDefault="00932CE1">
                                    <w:pPr>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>No</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="696" w:type="pct"/>

                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="460CF7AD" w14:textId="77777777" w:rsidR="00D461C5"
                                     w:rsidRDefault="00932CE1">
                                    <w:pPr>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>Date</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1809" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="7A868282" w14:textId="77777777" w:rsidR="00D461C5"
                                     w:rsidRDefault="00932CE1">
                                    <w:pPr>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>Contents</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="211" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="1549AD31" w14:textId="77777777" w:rsidR="00D461C5"
                                     w:rsidRDefault="00932CE1">
                                    <w:pPr>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>L</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="214" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="4685DCEF" w14:textId="77777777" w:rsidR="00D461C5"
                                     w:rsidRDefault="00932CE1">
                                    <w:pPr>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>H</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1021" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="18799624" w14:textId="77777777" w:rsidR="00D461C5"
                                     w:rsidRDefault="00932CE1">
                                    <w:pPr>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>Instructors</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="761" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="0B81AA57" w14:textId="77777777" w:rsidR="00D461C5"
                                     w:rsidRDefault="00932CE1">
                                    <w:pPr>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>Remark</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';


        foreach ($data as $item) {
            $list = '';
            $bold = '';
            if (isset($item['bolder']) && $item['bolder'] != 'no') {
                $bold = '<w:'.$item['bolder'].'/>';

            }

            $merge = '';
            if (isset($item['merge'])) {
                if($item['merge'] == 'start'){
                    $merge = '<w:vMerge w:val="restart"/>';
                }else {
                    $merge = '<w:vMerge/>';
                }

            }

            $item['content'] = str_replace("&", "&amp;", $item['content']);
            $item['remark'] = str_replace("&", "&amp;", $item['remark']);


            if ($item['list']) {

                foreach ($item['list'] as $listitem) {
                    $listitem = str_replace("&", "&amp;", $listitem);
                    $list .= '<w:p w:rsidR="001925F5" w:rsidRPr="001925F5" w:rsidRDefault="001925F5"
                                     w:rsidP="001925F5">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:spacing w:before="60" w:after="60" w:line="240" w:lineRule="auto"/>
                                        <w:ind w:left="397" w:hanging="227"/>
                                        <w:jc w:val="left"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r w:rsidRPr="001925F5">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                        </w:rPr>
                                        <w:t><w:sym w:font="Symbol" w:char="002D"/> ' . $listitem . '</w:t>
                                    </w:r>
                                </w:p>';
                }


            }
            $wordML .= '<w:tr w:rsidR="00D461C5" w14:paraId="4FC79E1D" w14:textId="77777777" w:rsidTr="00D7433F">
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="288" w:type="pct"/>
                                <w:tcMar>
                                    <w:top w:w="0" w:type="auto"/>
                                    <w:bottom w:w="0" w:type="auto"/>
                                </w:tcMar>
                                <w:vAlign w:val="center"/>
                            </w:tcPr>
                            <w:p w14:paraId="5D49CB80" w14:textId="77777777" w:rsidR="00D461C5"
                                 w:rsidRDefault="00932CE1">
                                <w:pPr>
                                    <w:spacing w:before="60" w:after="60"/>
                                    <w:jc w:val="center"/>
                                    <w:textAlignment w:val="center"/>
                                </w:pPr>
                                <w:r>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:color w:val="000000"/>
                                        <w:position w:val="-3"/>
                                         ' . $bold . '
                                    </w:rPr>
                                    <w:t>' . $item['no'] . '</w:t>
                                </w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="696" w:type="pct"/>
                                '.$merge.'
                                <w:tcMar>
                                    <w:top w:w="0" w:type="auto"/>
                                    <w:bottom w:w="0" w:type="auto"/>
                                </w:tcMar>
                                <w:vAlign w:val="center"/>
                            </w:tcPr>
                            <w:p w14:paraId="7310D1E7" w14:textId="77777777" w:rsidR="00D461C5"
                                 w:rsidRDefault="00932CE1">
                                <w:pPr>
                                    <w:spacing w:before="60" w:after="60"/>
                                    <w:jc w:val="center"/>
                                    <w:textAlignment w:val="center"/>
                                </w:pPr>
                                <w:r>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:color w:val="000000"/>
                                        <w:position w:val="-3"/>
                                    </w:rPr>
                                    <w:t>' . $item['date'] . '</w:t>
                                </w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="1809" w:type="pct"/>
                                <w:tcMar>
                                    <w:top w:w="0" w:type="auto"/>
                                    <w:bottom w:w="0" w:type="auto"/>
                                </w:tcMar>
                                <w:vAlign w:val="center"/>
                            </w:tcPr>
                            <w:p w14:paraId="6513CF1E" w14:textId="77777777" w:rsidR="00D461C5"
                                     w:rsidRDefault="00932CE1">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            ' . $bold . '
                                        </w:rPr>
                                        <w:t xml:space="preserve">' . $item['content'] . '</w:t>
                                    </w:r>
                                </w:p>
                            ' . $list . '
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="211" w:type="pct"/>
                                <w:tcMar>
                                    <w:top w:w="0" w:type="auto"/>
                                    <w:bottom w:w="0" w:type="auto"/>
                                </w:tcMar>
                                <w:vAlign w:val="center"/>
                            </w:tcPr>
                            <w:p w14:paraId="2E098E8F" w14:textId="639C64BA" w:rsidR="00D461C5"
                                 w:rsidRDefault="00DF23EA">
                                <w:pPr>
                                    <w:spacing w:before="60" w:after="60"/>
                                    <w:jc w:val="center"/>
                                    <w:textAlignment w:val="center"/>
                                </w:pPr>
                                <w:r>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:color w:val="000000"/>
                                        ' . $bold . '
                                    </w:rPr>
                                    <w:t>' . $item['level'] . '</w:t>
                                </w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="214" w:type="pct"/>
                                '.$merge.'
                                <w:tcMar>
                                    <w:top w:w="0" w:type="auto"/>
                                    <w:bottom w:w="0" w:type="auto"/>
                                </w:tcMar>
                                <w:vAlign w:val="center"/>
                            </w:tcPr>
                            <w:p w14:paraId="3FAEDCE4" w14:textId="01D11368" w:rsidR="00D461C5"
                                 w:rsidRDefault="00DF23EA">
                                <w:pPr>
                                    <w:spacing w:before="60" w:after="60"/>
                                    <w:jc w:val="center"/>
                                    <w:textAlignment w:val="center"/>
                                </w:pPr>
                                <w:r>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:color w:val="000000"/>
                                        ' . $bold . '
                                    </w:rPr>
                                    <w:t>' . $item['hour'] . '</w:t>
                                </w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="1021" w:type="pct"/>
                                '.$merge.'
                                <w:tcMar>
                                    <w:top w:w="0" w:type="auto"/>
                                    <w:bottom w:w="0" w:type="auto"/>
                                </w:tcMar>
                                <w:vAlign w:val="center"/>
                            </w:tcPr>
                            <w:p w14:paraId="325CE19C" w14:textId="77777777" w:rsidR="00D461C5"
                                 w:rsidRDefault="00932CE1">
                                <w:pPr>
                                    <w:spacing w:before="60" w:after="60"/>
                                    <w:jc w:val="center"/>
                                    <w:textAlignment w:val="center"/>
                                </w:pPr>
                                <w:r>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:color w:val="000000"/>
                                        <w:position w:val="-3"/>
                                    </w:rPr>
                                    <w:t xml:space="preserve">' . $item['instructor'] . '</w:t>
                                </w:r>
                            </w:p>
                        </w:tc>
                        <w:tc>
                            <w:tcPr>
                                <w:tcW w:w="761" w:type="pct"/>
                                '.$merge.'
                                <w:tcMar>
                                    <w:top w:w="0" w:type="auto"/>
                                    <w:bottom w:w="0" w:type="auto"/>
                                </w:tcMar>
                                <w:vAlign w:val="center"/>
                            </w:tcPr>
                            <w:p w14:paraId="3047DBCA" w14:textId="77777777" w:rsidR="00D461C5"
                                 w:rsidRDefault="00932CE1">
                                <w:pPr>
                                    <w:spacing w:before="60" w:after="60"/>
                                    <w:jc w:val="center"/>
                                    <w:textAlignment w:val="center"/>
                                </w:pPr>
                                <w:r>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:color w:val="000000"/>
                                        <w:position w:val="-3"/>
                                    </w:rPr>
                                    <w:t xml:space="preserve">' . $item['remark'] . '</w:t>
                                </w:r>

                            </w:p>
                        </w:tc>
                    </w:tr>';


        }

        $wordML .= '</w:tbl>';


        return $wordML;
    }

    public function prepareThirteenTable($course)
    {

        //first we count the subjects
        $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($course->getId());

        $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculums->getFirstItem()->getId());

        $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId())->addFieldToFilter('status', 1)->addFieldToFilter('require_exam', 1);//->addFieldToFilter('status', 1)->addFieldToFilter('subject_exam', 0)

        $count = count($subjects);
        //total = 15688
        //fix the first 2  and two last culumns
        //486 - 155%, 2286 - 729%, 789 - 251%, 789 - 251%
        //we have 11338 - 3614%  left
        //we calculate the dynamic columns first to make the width fit perfectly
        //divide to count + 1, because we have Staff ID culumn will get the last width





        $noWidth = 490;
        $noWidthPercent = 156;

        $nameWidth = 2930;
        $nameWidthPercent = 934;
        $idWidth = 1260;
        $idWidthPercent = 402;
        $dynWidth = 9430;
        $dynWidthPercent = 3006;


        $passWidth = 789;
        $passWidthPercent = 251;
        $failWidth = 789;
        $failWidthPercent = 251;


        $nameWidthPercent = 729;
        if($count < 5){
            $nameWidth = 4180;
            $nameWidthPercent = 1332;
            $idWidth = 3040;
            $idWidthPercent = 969;
            $dynWidth = 6400;
            $dynWidthPercent = 2040;

        }

        $totalWidth = 1578;
        $totalWidthPercent = 502;

        $left = $dynWidth;
        $leftPercent = $dynWidthPercent;

        $width = floor($left / ($count));
        $widthPercent = floor($width * 5000/15688);

        /*$sWidthPercent = $leftPercent - ($widthPercent * $count);
        $totalDynWidth = $count * $width;
        $totalDynWidthPercent = $widthPercent * $count;
        $staffWidth = $left - ($totalDynWidth);
        $staffWidthPercent = $leftPercent - $totalDynWidthPercent;*/



        $dynamicCol = '';
        for ($i =0; $i < $count; $i++){
            $dynamicCol .= '<w:gridCol w:w="'.$width.'"/>';
        }

        $dynamicColName = '';

        if ($count) {
            foreach ($subjects as $sub) {
                $shortCode = $sub->getSubjectShortcode();
                if ($shortCode == '') {
                    $shortCode = $sub->getSubjectName();
                }
                $dynamicColName .= '<w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$widthPercent.'" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="3B264A4C" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$shortCode.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>';

            }

        }

        $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($course)->setOrder('position', 'ASC');

        $traineeInfo = '';
        $i = 1;
        foreach ($trainees as $_trainee) {

            $trainee = Mage::getModel('bs_trainee/trainee')->load($_trainee->getId());
            $code = $trainee->getTraineeCode();
            $dept = $trainee->getTraineeDept();
            if ($dept == '') {
                $dept = 'TC';
            }

            $vaecoId = $trainee->getVaecoId();
            if ($vaecoId != '') {
                $code = $vaecoId;

            }

            $totalPass = 0;
            $totalFail = 0;

            $scoreInfo = '';


            if ($count) {
                foreach ($subjects as $sub) {

                    $examresult = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('trainee_id', $trainee->getId())->addFieldToFilter('subject_id', $sub->getId())->getFirstItem();

                    $finalMark = '';
                    $bg = '';
                    if ($examresult->getId()) {
                        $firstMark = (float)$examresult->getFirstMark();
                        //$secondMark = (float)$examresult->getSecondMark();
                        //$thirdMark = (float)$examresult->getThirdMark();

                        $finalMark = $firstMark;//max($firstMark, $secondMark, $thirdMark);


                        if ($finalMark >= 75) {
                            $totalPass += 1;
                        } else {
                            $bg = '<w:shd w:val="clear" w:color="auto" w:fill="D9D9D9" w:themeFill="background1"
                                           w:themeFillShade="D9"/>';
                            $totalFail += 1;
                        }

                    }

                    $scoreInfo .= '<w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$widthPercent.'" w:type="pct"/>
                                    '.$bg.'
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="1863CB74" w14:textId="5D1B32EE" w:rsidR="00AB2A01"
                                     w:rsidRDefault="00C17587">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>

                                        </w:rPr>
                                        <w:t>'.$finalMark.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>';

                }

            }


            $noticeBg = '';
            if ($totalFail > 0) {
                $noticeBg = '<w:shd w:val="clear" w:color="auto" w:fill="BFBFBF" w:themeFill="background1"
                                           w:themeFillShade="BF"/>';
            }


            if ($totalFail / $totalPass > 0.5 && $totalFail > 1) {
                $noticeBg = '<w:shd w:val="clear" w:color="auto" w:fill="FF0000"/>';
            }


            if ($totalPass < 10) {
                $totalPass = '0' . $totalPass;
            }
            if ($totalFail < 10) {
                $totalFail = '0' . $totalFail;
            }




            $traineeInfo .= '<w:tr w:rsidR="00AB2A01" w14:paraId="3F64F613" w14:textId="77777777" w:rsidTr="003E19C9">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$noWidthPercent.'" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="77714B37" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$i.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$nameWidthPercent.'" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="505F6B80" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$trainee->getTraineeName().'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$idWidthPercent.'" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="77ABE457" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$code.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>

                            '.$scoreInfo.'
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$passWidthPercent.'" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="5A402267" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$totalPass.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$failWidthPercent.'" w:type="pct"/>
                                    '.$noticeBg.'
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="6974C2E0" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>

                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>

                                        </w:rPr>
                                        <w:t>'.$totalFail.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';






            $i++;

        }

        $wordML = '<w:tbl>
                        <w:tblPr>
                            <w:tblStyle w:val="TableGridPHPDOCX"/>
                            <w:tblW w:w="5000" w:type="pct"/>
                            <w:tblBorders>
                                <w:top w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:bottom w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:insideH w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:insideV w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                            </w:tblBorders>
                            <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0"
                                       w:noHBand="0" w:noVBand="1"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="'.$noWidth.'"/>
                            <w:gridCol w:w="'.$nameWidth.'"/>
                            <w:gridCol w:w="'.$idWidth.'"/>
                            '.$dynWidth.'
                            <w:gridCol w:w="'.$passWidth.'"/>
                            <w:gridCol w:w="'.$failWidth.'"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="00AB2A01" w14:paraId="5997B4E7" w14:textId="77777777" w:rsidTr="003E19C9">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$noWidthPercent.'" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="3FB56386" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>No</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$nameWidthPercent.'" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="2CB7DDBA" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Trainee\'s name</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$idWidthPercent.'" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="24C1FE71" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Staff ID</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$dynWidthPercent.'" w:type="pct"/>
                                    <w:gridSpan w:val="'.$count.'"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="414AEEAC" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Exam score per chapter (%)</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$totalWidthPercent.'" w:type="pct"/>
                                    <w:gridSpan w:val="2"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="72B30A1B" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>

                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Total</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>
                        <w:tr w:rsidR="00AB2A01" w14:paraId="4973B847" w14:textId="77777777" w:rsidTr="003E19C9">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$noWidthPercent.'" w:type="pct"/>
                                    <w:vMerge/>
                                </w:tcPr>
                                <w:p w14:paraId="2C03EEF1" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="00AB2A01"/>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$nameWidthPercent.'" w:type="pct"/>
                                    <w:vMerge/>
                                </w:tcPr>
                                <w:p w14:paraId="2DF2520B" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="00AB2A01"/>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$idWidthPercent.'" w:type="pct"/>
                                    <w:vMerge/>
                                </w:tcPr>
                                <w:p w14:paraId="44423634" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="00AB2A01"/>
                            </w:tc>
                            '.$dynamicColName.'
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$passWidthPercent.'" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="61ED4F12" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Pass</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$failWidthPercent.'" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="67FF552E" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Fail</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>
                        '.$traineeInfo.'

                    </w:tbl>';

        return $wordML;

    }

    public function prepareThirteenReTable($course)
    {

        //first we count the subjects
        $examresults = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('first_mark', array('lt' => 75));


        $countER = count($examresults);
        $subjectIds = array();
        $traineeIds = array();
        if ($countER) {
            foreach ($examresults as $exre) {
                $subjectIds[$exre->getSubjectId()] = 1;
                $traineeIds[$exre->getTraineeId()] = 1;
            }

        }
        $subjectIds = array_keys($subjectIds);
        $traineeIds = array_keys($traineeIds);

        $count = count($subjectIds);
        //total = 15688
        //fix the first 2  and two last culumns
        // 486 - 155%, 2286 - 729%, 789 - 251%, 789 - 251%
        //we have 11338 - 3614%  left
        //we calculate the dynamic columns first to make the width fit perfectly
        // divide to count + 1, because we have Staff ID culumn will get the last width
        $left = 11338;
        $leftPercent = 3614;

        $width = floor($left / ($count+1));
        $sWidth = $left - $count*$width;
        $widthPercent = floor($width * 5000/15688);
        $sWidthPercent = $leftPercent - ($widthPercent * $count);
        $totalDynWidth = $count * $width;
        $totalDynWidthPercent = $widthPercent * $count;
        $staffWidth = $left - ($totalDynWidth);
        $staffWidthPercent = $leftPercent - $totalDynWidthPercent;


        $dynamicCol = '';
        for ($i =0; $i < $count; $i++){
            $dynamicCol .= '<w:gridCol w:w="'.$width.'"/>';
        }

        $dynamicColName = '';

        if ($count) {
            foreach ($subjectIds as $subId) {
                $sub = Mage::getModel('bs_subject/subject')->load($subId);
                $shortCode = $sub->getSubjectShortcode();
                if ($shortCode == '') {
                    $shortCode = $sub->getSubjectName();
                }
                $dynamicColName .= '<w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$widthPercent.'" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="3B264A4C" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$shortCode.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>';

            }

        }



        $traineeInfo = '';
        $i = 1;
        foreach ($traineeIds as $traineeId) {

            $trainee = Mage::getModel('bs_trainee/trainee')->load($traineeId);

            $code = $trainee->getTraineeCode();
            $dept = $trainee->getTraineeDept();
            if ($dept == '') {
                $dept = 'TC';
            }

            $vaecoId = $trainee->getVaecoId();
            if ($vaecoId != '') {
                $code = $vaecoId;

            }

            $totalPass = 0;
            $totalFail = 0;

            $scoreInfo = '';


            if ($count) {
                foreach ($subjectIds as $subId) {

                    $examresult = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('trainee_id', $trainee->getId())->addFieldToFilter('subject_id', $subId)->getFirstItem();

                    $finalMark = '';
                    $bg = '';
                    if ($examresult->getId()) {

                        $secondMark = (float)$examresult->getSecondMark();
                        //$thirdMark = (float)$examresult->getThirdMark();

                        $finalMark = $secondMark;


                        if ($finalMark >= 75) {
                            $totalPass += 1;
                        } elseif ($finalMark < 75 && $finalMark > 0) {
                            $bg = "style='background:#C6C6C6;'";
                            $totalFail += 1;
                        } else {
                            $finalMark = 'N/A';
                            $bg = '';
                        }


                    }
                    $scoreInfo .= '<w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$widthPercent.'" w:type="pct"/>
                                    '.$bg.'
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="1863CB74" w14:textId="5D1B32EE" w:rsidR="00AB2A01"
                                     w:rsidRDefault="00C17587">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>

                                        </w:rPr>
                                        <w:t>'.$finalMark.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>';
                }



            }


            $noticeBg = '';
            if ($totalFail > 0) {
                $noticeBg = '<w:shd w:val="clear" w:color="auto" w:fill="BFBFBF" w:themeFill="background1"
                                           w:themeFillShade="BF"/>';
            }


            if ($totalFail / $totalPass > 0.5 && $totalFail > 1) {
                $noticeBg = '<w:shd w:val="clear" w:color="auto" w:fill="FF0000"/>';
            }


            if ($totalPass < 10) {
                $totalPass = '0' . $totalPass;
            }
            if ($totalFail < 10) {
                $totalFail = '0' . $totalFail;
            }




            $traineeInfo .= '<w:tr w:rsidR="00AB2A01" w14:paraId="3F64F613" w14:textId="77777777" w:rsidTr="003E19C9">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="155" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="77714B37" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$i.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="729" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="505F6B80" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$trainee->getTraineeName().'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$staffWidthPercent.'" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="77ABE457" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$code.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>

                            '.$scoreInfo.'
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="251" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="5A402267" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$totalPass.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="251" w:type="pct"/>
                                    '.$noticeBg.'
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="6974C2E0" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>

                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>

                                        </w:rPr>
                                        <w:t>'.$totalFail.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';






            $i++;

        }

        $wordML = '<w:tbl>
                        <w:tblPr>
                            <w:tblStyle w:val="TableGridPHPDOCX"/>
                            <w:tblW w:w="5000" w:type="pct"/>
                            <w:tblBorders>
                                <w:top w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:bottom w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:insideH w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:insideV w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                            </w:tblBorders>
                            <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0"
                                       w:noHBand="0" w:noVBand="1"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="486"/>
                            <w:gridCol w:w="2286"/>
                            <w:gridCol w:w="'.$staffWidth.'"/>
                            '.$dynamicCol.'
                            <w:gridCol w:w="789"/>
                            <w:gridCol w:w="789"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="00AB2A01" w14:paraId="5997B4E7" w14:textId="77777777" w:rsidTr="003E19C9">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="155" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="3FB56386" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>No</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="729" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="2CB7DDBA" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Trainee\'s name</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$staffWidthPercent.'" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="24C1FE71" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Staff ID</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$totalDynWidthPercent.'" w:type="pct"/>
                                    <w:gridSpan w:val="'.$count.'"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="414AEEAC" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Exam score per chapter (%)</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="502" w:type="pct"/>
                                    <w:gridSpan w:val="2"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="72B30A1B" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>

                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Total</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>
                        <w:tr w:rsidR="00AB2A01" w14:paraId="4973B847" w14:textId="77777777" w:rsidTr="003E19C9">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="155" w:type="pct"/>
                                    <w:vMerge/>
                                </w:tcPr>
                                <w:p w14:paraId="2C03EEF1" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="00AB2A01"/>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="729" w:type="pct"/>
                                    <w:vMerge/>
                                </w:tcPr>
                                <w:p w14:paraId="2DF2520B" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="00AB2A01"/>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="'.$staffWidthPercent.'" w:type="pct"/>
                                    <w:vMerge/>
                                </w:tcPr>
                                <w:p w14:paraId="44423634" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="00AB2A01"/>
                            </w:tc>
                            '.$dynamicColName.'
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="251" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="61ED4F12" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Pass</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="251" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w14:paraId="67FF552E" w14:textId="77777777" w:rsidR="00AB2A01"
                                     w:rsidRDefault="007B460F">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="40"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:eastAsia="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Fail</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>
                        '.$traineeInfo.'

                    </w:tbl>';

        return $wordML;

    }

    public function prepareFifteenTable($data, $spacing = array())
    {

        $before = 60;
        $after = 60;

        if(count($spacing) == 2){
            $before = floatval($spacing[0]) * 20;
            $after = floatval($spacing[1]) * 20;

        }

        $wordML = '<w:tbl>
                        <w:tblPr>
                            <w:tblStyle w:val="TableGridPHPDOCX"/>
                            <w:tblW w:w="5000" w:type="pct"/>
                            <w:tblBorders>
                                <w:top w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:bottom w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:insideH w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                                <w:insideV w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                            </w:tblBorders>
                            <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0"
                                       w:noHBand="0" w:noVBand="1"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="766"/>
                            <w:gridCol w:w="5529"/>
                            <w:gridCol w:w="1047"/>
                            <w:gridCol w:w="1349"/>
                            <w:gridCol w:w="1495"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="00863CBB" w:rsidTr="00EF29B1">
                            <w:trPr>
                                <w:tblHeader/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="376" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="00EB1329">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:spacing w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:lastRenderedPageBreak/>
                                        <w:t>No</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="2714" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="00EB1329">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:spacing w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>Description</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="514" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="00EB1329">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:spacing w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>Level (if any)</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="662" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="00EB1329">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:spacing w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>Duration (hour)</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="734" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="00EB1329">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        <w:spacing w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            <w:shd w:val="clear" w:color="auto" w:fill="E5E5E5"/>
                                        </w:rPr>
                                        <w:t>Remark</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';


        $i = 1;
        foreach ($data as $item) {

            $list = '';
            $bold = '';
            $class = 'secondList';
            if (isset($item['bolder']) && $item['bolder'] != 'no') {
                $bold = '<w:'.$item['bolder'].'/>';


            }
            if ($item['list']) {

                foreach ($item['list'] as $listitem) {

                    $listitem = str_replace("&", "&amp;", $listitem);

                    $list .= '<w:p w:rsidR="001925F5" w:rsidRPr="001925F5" w:rsidRDefault="001925F5"
                                     w:rsidP="001925F5">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:spacing w:before="'.$before.'" w:after="'.$after.'" w:line="240" w:lineRule="auto"/>
                                        <w:ind w:left="397" w:hanging="227"/>
                                        <w:jc w:val="left"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r w:rsidRPr="001925F5">
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve"><w:sym w:font="Symbol" w:char="002D"/> ' . $listitem . '</w:t>
                                    </w:r>
                                </w:p>';

                }


            }


            $wordML .= '<w:tr w:rsidR="00863CBB" w:rsidTr="00807BDE">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="376" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="007D0794">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:spacing w:before="'.$before.'" w:after="'.$after.'" w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            ' . $bold . '
                                        </w:rPr>
                                        <w:t>' . $item['no'] . '</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="2714" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="007D0794">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:spacing w:before="'.$before.'" w:after="'.$after.'" w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="left"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            ' . $bold . '
                                        </w:rPr>
                                        <w:t xml:space="preserve">' . str_replace("&","&amp;",$item['name']) . '</w:t>
                                    </w:r>
                                </w:p>
                                ' . $list . '
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="514" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="007D0794">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:spacing w:before="'.$before.'" w:after="'.$after.'" w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            ' . $bold . '
                                        </w:rPr>
                                        <w:t>' . $item['level'] . '</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="662" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="007D0794">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:spacing w:before="'.$before.'" w:after="'.$after.'" w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            ' . $bold . '
                                        </w:rPr>
                                        <w:t>' . $item['hour'] . '</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="734" w:type="pct"/>
                                    <w:tcMar>
                                        <w:top w:w="0" w:type="auto"/>
                                        <w:bottom w:w="0" w:type="auto"/>
                                    </w:tcMar>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00863CBB" w:rsidRDefault="007D0794" w:rsidP="007D0794">
                                    <w:pPr>
                                        <w:widowControl/>
                                        <w:spacing w:before="'.$before.'" w:after="'.$after.'" w:line="240" w:lineRule="auto"/>
                                        <w:jc w:val="center"/>
                                        <w:textAlignment w:val="center"/>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:eastAsia="Arial" w:cs="Arial"/>
                                            <w:color w:val="000000"/>
                                            <w:position w:val="-3"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">' . str_replace("&","&amp;",$item['remark']) . '</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';


            $i++;
        }


        $wordML .= '</w:tbl>';


        return $wordML;
    }

    public function prepareSeventeen($data){
        $wordML = '';
        foreach ($data as $item) {
            if(is_array($item)){

                if(count($item)){
                    $wordML .= '<w:tbl>
                                    <w:tblPr>
                                        <w:tblW w:w="5000" w:type="pct"/>
                                        <w:tblBorders>
                                            <w:top w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                            <w:left w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                            <w:bottom w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                            <w:right w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                            <w:insideH w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                            <w:insideV w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                        </w:tblBorders>
                                        <w:tblCellMar>
                                            <w:left w:w="57" w:type="dxa"/>
                                            <w:right w:w="57" w:type="dxa"/>
                                        </w:tblCellMar>
                                        <w:tblLook w:val="0000" w:firstRow="0" w:lastRow="0" w:firstColumn="0" w:lastColumn="0"
                                                   w:noHBand="0" w:noVBand="0"/>
                                    </w:tblPr>
                                    <w:tblGrid>
                                        <w:gridCol w:w="556"/>
                                        <w:gridCol w:w="6380"/>
                                        <w:gridCol w:w="566"/>
                                        <w:gridCol w:w="2683"/>
                                    </w:tblGrid>
                                    <w:tr w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidTr="0076083D">
                                        <w:trPr>
                                            <w:cantSplit/>
                                            <w:trHeight w:val="454"/>
                                            <w:tblHeader/>
                                        </w:trPr>
                                        <w:tc>
                                            <w:tcPr>
                                                <w:tcW w:w="273" w:type="pct"/>
                                                <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                                <w:vAlign w:val="center"/>
                                            </w:tcPr>
                                            <w:p w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidRDefault="003C58A8"
                                                 w:rsidP="00936348">
                                                <w:pPr>
                                                    <w:spacing w:before="40" w:after="40"/>
                                                    <w:ind w:left="-108" w:right="-108"/>
                                                    <w:jc w:val="center"/>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:b/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                </w:pPr>
                                                <w:r w:rsidRPr="00BC222A">
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:b/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                    <w:t>No.</w:t>
                                                </w:r>
                                            </w:p>
                                        </w:tc>
                                        <w:tc>
                                            <w:tcPr>
                                                <w:tcW w:w="3132" w:type="pct"/>
                                                <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                                <w:vAlign w:val="center"/>
                                            </w:tcPr>
                                            <w:p w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidRDefault="003C58A8"
                                                 w:rsidP="00936348">
                                                <w:pPr>
                                                    <w:spacing w:before="40" w:after="40"/>
                                                    <w:jc w:val="center"/>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:b/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                </w:pPr>
                                                <w:r w:rsidRPr="00BC222A">
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:b/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                    <w:t>Course Title</w:t>
                                                </w:r>
                                            </w:p>
                                        </w:tc>
                                        <w:tc>
                                            <w:tcPr>
                                                <w:tcW w:w="278" w:type="pct"/>
                                                <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                                <w:vAlign w:val="center"/>
                                            </w:tcPr>
                                            <w:p w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidRDefault="003C58A8"
                                                 w:rsidP="00936348">
                                                <w:pPr>
                                                    <w:spacing w:before="40" w:after="40"/>
                                                    <w:ind w:left="-108" w:right="-108"/>
                                                    <w:jc w:val="center"/>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:b/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                </w:pPr>
                                                <w:r w:rsidRPr="00BC222A">
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:b/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                    <w:t>Rev.</w:t>
                                                </w:r>
                                            </w:p>
                                        </w:tc>
                                        <w:tc>
                                            <w:tcPr>
                                                <w:tcW w:w="1317" w:type="pct"/>
                                                <w:vAlign w:val="center"/>
                                            </w:tcPr>
                                            <w:p w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidRDefault="003C58A8"
                                                 w:rsidP="007066ED">
                                                <w:pPr>
                                                    <w:spacing w:before="40" w:after="40"/>
                                                    <w:jc w:val="center"/>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:b/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                </w:pPr>
                                                <w:r w:rsidRPr="00BC222A">
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:b/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                    <w:t>Course Code</w:t>
                                                </w:r>
                                            </w:p>
                                        </w:tc>
                                    </w:tr>';
                                                        $i=1;
                                                        foreach ($item as $list) {

                                                            $name = str_replace("&", "&amp;", $list['name']);
                                                            $code = str_replace("&", "&amp;", $list['code']);
                                                            $wordML .= '<w:tr w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidTr="0076083D">
                                        <w:trPr>
                                            <w:cantSplit/>
                                            <w:trHeight w:val="454"/>
                                        </w:trPr>
                                        <w:tc>
                                            <w:tcPr>
                                                <w:tcW w:w="273" w:type="pct"/>
                                                <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                                <w:vAlign w:val="center"/>
                                            </w:tcPr>
                                            <w:p w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidRDefault="0076083D"
                                                 w:rsidP="0076083D">
                                                <w:pPr>
                                                    <w:spacing w:before="40" w:after="40"/>
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
                                                <w:tcW w:w="3132" w:type="pct"/>
                                                <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                                <w:vAlign w:val="center"/>
                                            </w:tcPr>
                                            <w:p w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidRDefault="005E6366"
                                                 w:rsidP="0076083D">
                                                <w:pPr>
                                                    <w:spacing w:before="40" w:after="40"/>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                </w:pPr>
                                                <w:r>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                    <w:t xml:space="preserve">'.$name.'</w:t>
                                                </w:r>
                                            </w:p>
                                        </w:tc>
                                        <w:tc>
                                            <w:tcPr>
                                                <w:tcW w:w="278" w:type="pct"/>
                                                <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                                <w:vAlign w:val="center"/>
                                            </w:tcPr>
                                            <w:p w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidRDefault="0076083D"
                                                 w:rsidP="00691949">
                                                <w:pPr>
                                                    <w:spacing w:before="40" w:after="40"/>
                                                    <w:ind w:left="-108" w:right="-108"/>
                                                    <w:jc w:val="center"/>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                </w:pPr>
                                                <w:r>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                    <w:t xml:space="preserve">'.$list['rev'].'</w:t>
                                                </w:r>
                                            </w:p>
                                        </w:tc>
                                        <w:tc>
                                            <w:tcPr>
                                                <w:tcW w:w="1317" w:type="pct"/>
                                                <w:vAlign w:val="center"/>
                                            </w:tcPr>
                                            <w:p w:rsidR="003C58A8" w:rsidRPr="00BC222A" w:rsidRDefault="005E6366"
                                                 w:rsidP="0076083D">
                                                <w:pPr>
                                                    <w:spacing w:before="40" w:after="40"/>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                </w:pPr>
                                                <w:r>
                                                    <w:rPr>
                                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                                        <w:bCs/>
                                                        <w:sz w:val="22"/>
                                                        <w:szCs w:val="22"/>
                                                    </w:rPr>
                                                    <w:t xml:space="preserve">'.$code.'/YYZZ</w:t>
                                                </w:r>
                                            </w:p>
                                        </w:tc>
                                    </w:tr>';
                        $i++;
                    }
                    $wordML .= '</w:tbl>';

                }
            }else {
                $item = str_replace("&", "&amp;", $item);
                $title = $item;
                $desc = '';
                if(strpos($item,"--desc")){
                    $titleArray = explode("--desc--",$title);
                    $title = $titleArray[0];
                    $desc = $titleArray[1];
                }
                $wordML .= '<w:p w:rsidR="003C58A8" w:rsidRDefault="002D28EA" w:rsidP="00C7254B">
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="right" w:leader="dot" w:pos="8820"/>
                                        <w:tab w:val="right" w:leader="dot" w:pos="9720"/>
                                    </w:tabs>
                                    <w:spacing w:before="240" w:after="120"/>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                </w:pPr>
                                <w:r w:rsidR="00BC222A" w:rsidRPr="00BC222A">
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                        <w:b/>
                                    </w:rPr>
                                    <w:t xml:space="preserve">'.$title.'</w:t>
                                </w:r>
                            </w:p>';

                if($desc != ''){
                    $wordML .= '<w:p w:rsidR="003C58A8" w:rsidRDefault="002D28EA" w:rsidP="00C7254B">
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="right" w:leader="dot" w:pos="8820"/>
                                        <w:tab w:val="right" w:leader="dot" w:pos="9720"/>
                                    </w:tabs>
                                    <w:spacing w:before="240" w:after="120"/>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                </w:pPr>
                                <w:r w:rsidR="00BC222A" w:rsidRPr="00BC222A">
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                    <w:t xml:space="preserve">'.$desc.'</w:t>
                                </w:r>
                            </w:p>';
                }
            }
        }

        return $wordML;

    }

    public function prepareMaterialList($data){
        $wordML = '';
        foreach ($data as $item) {
            if(is_array($item)){

                if(count($item)){
                    $wordML .= '<w:tbl>
                        <w:tblPr>
                            <w:tblW w:w="5000" w:type="pct"/>
                            <w:tblBorders>
                                <w:top w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                <w:left w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                <w:bottom w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                <w:right w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                <w:insideH w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                                <w:insideV w:val="single" w:sz="8" w:space="0" w:color="auto"/>
                            </w:tblBorders>
                            <w:tblCellMar>
                                <w:left w:w="28" w:type="dxa"/>
                                <w:right w:w="28" w:type="dxa"/>
                            </w:tblCellMar>
                            <w:tblLook w:val="0000" w:firstRow="0" w:lastRow="0" w:firstColumn="0" w:lastColumn="0"
                                       w:noHBand="0" w:noVBand="0"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="560"/>
                            <w:gridCol w:w="4206"/>
                            <w:gridCol w:w="816"/>
                            <w:gridCol w:w="1779"/>
                            <w:gridCol w:w="4395"/>
                            <w:gridCol w:w="2149"/>
                            <w:gridCol w:w="549"/>
                            <w:gridCol w:w="1230"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="00520023" w:rsidRPr="00501705" w:rsidTr="00705FA2">
                            <w:trPr>
                                <w:cantSplit/>
                                <w:trHeight w:val="299"/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="179" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="00475AFA">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>No.</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1341" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="00475AFA">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Course Title</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="260" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="00475AFA">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Course Rev.</w:t>
                                    </w:r>
                                </w:p>
                                
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="567" w:type="pct"/>
                                    <w:vMerge w:val="restart"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="00475AFA">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Course Code</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="2653" w:type="pct"/>
                                    <w:gridSpan w:val="4"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00242E7E"
                                     w:rsidP="00CC0509">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Training Course Manual</w:t>
                                    </w:r>

                                </w:p>
                            </w:tc>
                        </w:tr>
                        <w:tr w:rsidR="00520023" w:rsidRPr="00501705" w:rsidTr="00705FA2">
                            <w:trPr>
                                <w:cantSplit/>
                                <w:trHeight w:val="262"/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="179" w:type="pct"/>
                                    <w:vMerge/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="00475AFA">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                        </w:rPr>
                                    </w:pPr>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1341" w:type="pct"/>
                                    <w:vMerge/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="00475AFA">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="260" w:type="pct"/>
                                    <w:vMerge/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="00475AFA">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="567" w:type="pct"/>
                                    <w:vMerge/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="00475AFA">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1401" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="001F24AE">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Description</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="685" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00B669FD" w:rsidRPr="00A237AA" w:rsidRDefault="00B669FD"
                                     w:rsidP="00246435">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Code</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="175" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="001F24AE">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Rev</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="392" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" w:themeFill="background1"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00520023" w:rsidRPr="00A237AA" w:rsidRDefault="00520023"
                                     w:rsidP="001F24AE">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Date</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';
                    $i=1;
                    foreach ($item as $list) {
                        $wordML .= '<w:tr w:rsidR="00A54BD3" w:rsidRPr="00501705" w:rsidTr="00811A02">
                            <w:trPr>
                                <w:cantSplit/>
                                <w:trHeight w:val="578"/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="179" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A54BD3" w:rsidRPr="00A237AA" w:rsidRDefault="00811A02"
                                     w:rsidP="00811A02">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        </w:rPr>
                                        <w:t>'.$i.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1341" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A54BD3" w:rsidRPr="00A237AA" w:rsidRDefault="00A54BD3"
                                     w:rsidP="00246435">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="57"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$list['name'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="260" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A54BD3" w:rsidRPr="00A237AA" w:rsidRDefault="00A54BD3"
                                     w:rsidP="00246435">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="-108" w:right="-108"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$list['rev'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="567" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A54BD3" w:rsidRPr="00A237AA" w:rsidRDefault="00A54BD3"
                                     w:rsidP="00246435">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="57"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$list['code'].'/YYZZ</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1401" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A54BD3" w:rsidRPr="00A237AA" w:rsidRDefault="00A54BD3"
                                     w:rsidP="00246435">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="57"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$list['man_name'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="685" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A54BD3" w:rsidRPr="00A237AA" w:rsidRDefault="00A54BD3"
                                     w:rsidP="00246435">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:ind w:left="57"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:color w:val="000000" w:themeColor="text1"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$list['man_code'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="175" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A54BD3" w:rsidRPr="00A237AA" w:rsidRDefault="00A54BD3"
                                     w:rsidP="00246435">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:lang w:val="vi-VN"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$list['man_rev'].'</w:t>
                                    </w:r>
                                    
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="392" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A54BD3" w:rsidRPr="00A237AA" w:rsidRDefault="00A54BD3"
                                     w:rsidP="00246435">
                                    <w:pPr>
                                        <w:spacing w:before="20" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:lang w:val="vi-VN"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00A237AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                            <w:lang w:val="vi-VN"/>
                                        </w:rPr>
                                        <w:t>'.$list['man_date'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';
                        $i++;
                    }
                    $wordML .= '</w:tbl>';

                }
            }else {
                $item = str_replace("&", "&amp;", $item);
                $title = $item;
                $desc = '';
                if(strpos($item,"--desc")){
                    $titleArray = explode("--desc--",$title);
                    $title = $titleArray[0];
                    $desc = $titleArray[1];
                }
                $wordML .= '<w:p w:rsidR="003C58A8" w:rsidRDefault="002D28EA" w:rsidP="00C7254B">
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="right" w:leader="dot" w:pos="8820"/>
                                        <w:tab w:val="right" w:leader="dot" w:pos="9720"/>
                                    </w:tabs>
                                    <w:spacing w:before="240" w:after="120"/>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                </w:pPr>
                                <w:r w:rsidR="00BC222A" w:rsidRPr="00BC222A">
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                        <w:b/>
                                    </w:rPr>
                                    <w:t xml:space="preserve">'.$title.'</w:t>
                                </w:r>
                            </w:p>';

                if($desc != ''){
                    $wordML .= '<w:p w:rsidR="003C58A8" w:rsidRDefault="002D28EA" w:rsidP="00C7254B">
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="right" w:leader="dot" w:pos="8820"/>
                                        <w:tab w:val="right" w:leader="dot" w:pos="9720"/>
                                    </w:tabs>
                                    <w:spacing w:before="240" w:after="120"/>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                </w:pPr>
                                <w:r w:rsidR="00BC222A" w:rsidRPr="00BC222A">
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                    <w:t xml:space="preserve">'.$desc.'</w:t>
                                </w:r>
                            </w:p>';
                }
            }
        }

        return $wordML;

    }

    public function prepareRosterData($data){
        $wordML = '';
        foreach ($data as $item) {
            if(is_array($item)){

                if(count($item)){
                    $wordML .= '<w:tbl>
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
                            <w:tblLayout w:type="fixed"/>
                            <w:tblCellMar>
                                <w:left w:w="57" w:type="dxa"/>
                                <w:right w:w="57" w:type="dxa"/>
                            </w:tblCellMar>
                            <w:tblLook w:val="01E0" w:firstRow="1" w:lastRow="1" w:firstColumn="1" w:lastColumn="1"
                                       w:noHBand="0" w:noVBand="0"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="533"/>
                            <w:gridCol w:w="2722"/>
                            <w:gridCol w:w="1136"/>
                            <w:gridCol w:w="5246"/>
                            <w:gridCol w:w="2267"/>
                            <w:gridCol w:w="1416"/>
                            <w:gridCol w:w="1133"/>
                            <w:gridCol w:w="1243"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="002700E7" w:rsidRPr="00BD76E1" w:rsidTr="002700E7">
                            <w:trPr>
                                <w:trHeight w:val="550"/>
                                <w:tblHeader/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="170" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="008361D8" w:rsidRPr="00E314AA" w:rsidRDefault="008361D8"
                                     w:rsidP="008361D8">
                                    <w:pPr>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>No</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="867" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="008361D8" w:rsidRPr="00E314AA" w:rsidRDefault="008361D8"
                                     w:rsidP="008361D8">
                                    <w:pPr>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Name</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="362" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="008361D8" w:rsidRPr="00E314AA" w:rsidRDefault="008361D8"
                                     w:rsidP="008361D8">
                                    <w:pPr>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Staff ID</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1671" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="008361D8" w:rsidRPr="00E314AA" w:rsidRDefault="008361D8"
                                     w:rsidP="008361D8">
                                    <w:pPr>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Approved Course</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="722" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="008361D8" w:rsidRPr="00E314AA" w:rsidRDefault="008361D8"
                                     w:rsidP="008361D8">
                                    <w:pPr>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Approved Function</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="451" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="008361D8" w:rsidRPr="00E314AA" w:rsidRDefault="008361D8"
                                     w:rsidP="00E314AA">
                                    <w:pPr>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Approval Doc.</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="361" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="008361D8" w:rsidRPr="00E314AA" w:rsidRDefault="008361D8"
                                     w:rsidP="00E314AA">
                                    <w:pPr>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Approval date</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="396" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="008361D8" w:rsidRPr="00E314AA" w:rsidRDefault="008361D8"
                                     w:rsidP="00E314AA">
                                    <w:pPr>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>Expiry date</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';
                    $i=1;
                    foreach ($item as $list) {

                        $new = '';
                        $update = '';
                        $name = Mage::helper('bs_traininglist')->convertToUnsign($list['name'], false, true);
                        $course = str_replace("&", "&amp;", $list['course']);
                        $function = str_replace("&", "&amp;", $list['function']);

                        if($list['new']){
                            $new = '<w:highlight w:val="yellow"/>';
                        }
                        if($list['update']){
                            $update = '<w:highlight w:val="yellow"/>';
                        }


                        $wordML .= '<w:tr w:rsidR="002700E7" w:rsidRPr="00BD76E1" w:rsidTr="00446EF3">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="170" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00172655" w:rsidRPr="00E314AA" w:rsidRDefault="00510CEA"
                                     w:rsidP="00E314AA">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$new.'
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$new.'
                                        </w:rPr>
                                        <w:t>'.$i.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="867" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00172655" w:rsidRPr="00E314AA" w:rsidRDefault="00510CEA"
                                     w:rsidP="00B12E31">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$new.'
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$new.'
                                        </w:rPr>
                                        <w:t>'.$name.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="362" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00172655" w:rsidRPr="00E314AA" w:rsidRDefault="00510CEA"
                                     w:rsidP="00172655">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$new.'
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$new.'
                                        </w:rPr>
                                        <w:t>'.$list['vaeco_id'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1671" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00172655" w:rsidRPr="00E314AA" w:rsidRDefault="00510CEA"
                                     w:rsidP="003F027D">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$new.'
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$new.'
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$course.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="722" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00172655" w:rsidRPr="00E314AA" w:rsidRDefault="00510CEA"
                                     w:rsidP="003F027D">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$update.'
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                            '.$update.'
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$function.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="451" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00172655" w:rsidRPr="00E314AA" w:rsidRDefault="00510CEA"
                                     w:rsidP="00446EF3">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$list['doc'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="361" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00172655" w:rsidRPr="002700E7" w:rsidRDefault="00510CEA"
                                     w:rsidP="00446EF3">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="002700E7">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$list['approved_date'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="396" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00172655" w:rsidRPr="00E314AA" w:rsidRDefault="00510CEA"
                                     w:rsidP="00446EF3">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E314AA">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:bCs/>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                        <w:t>'.$list['expire_date'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';

                        $i++;
                    }
                    $wordML .= '</w:tbl>';

                }
            }else {
                $item = str_replace("&", "&amp;", $item);
                $title = $item;
                $desc = '';
                if(strpos($item,"--desc")){
                    $titleArray = explode("--desc--",$title);
                    $title = $titleArray[0];
                    $desc = $titleArray[1];
                }
                $wordML .= '<w:p w:rsidR="003C58A8" w:rsidRDefault="002D28EA" w:rsidP="00C7254B">
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="right" w:leader="dot" w:pos="8820"/>
                                        <w:tab w:val="right" w:leader="dot" w:pos="9720"/>
                                    </w:tabs>
                                    <w:spacing w:before="240" w:after="120"/>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                </w:pPr>
                                <w:r w:rsidR="00BC222A" w:rsidRPr="00BC222A">
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                        <w:b/>
                                    </w:rPr>
                                    <w:t xml:space="preserve">'.$title.'</w:t>
                                </w:r>
                            </w:p>';

                if($desc != ''){
                    $wordML .= '<w:p w:rsidR="003C58A8" w:rsidRDefault="002D28EA" w:rsidP="00C7254B">
                                <w:pPr>
                                    <w:tabs>
                                        <w:tab w:val="right" w:leader="dot" w:pos="8820"/>
                                        <w:tab w:val="right" w:leader="dot" w:pos="9720"/>
                                    </w:tabs>
                                    <w:spacing w:before="240" w:after="120"/>
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                </w:pPr>
                                <w:r w:rsidR="00BC222A" w:rsidRPr="00BC222A">
                                    <w:rPr>
                                        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        <w:sz w:val="22"/>
                                        <w:szCs w:val="22"/>
                                    </w:rPr>
                                    <w:t xml:space="preserve">'.$desc.'</w:t>
                                </w:r>
                            </w:p>';
                }
            }
        }

        return $wordML;

    }

    public function prepareStandby($course){
        $standbyInstructor = $course->getStandbyInstructor();
        $standbyRoster = $course->getStandbyRoster();


        $standbyContent = '';
        $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'standby_content')->getFirstItem();
        if($shortcut->getId() && $standbyRoster){

            $standbyContent = $shortcut->getDescription();
        }




        if($standbyInstructor == '' && !$standbyRoster){
            $standbyContent = '';
        }

        $standbyML = '<w:tbl>
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
                                <w:left w:w="57" w:type="dxa"/>
                                <w:right w:w="57" w:type="dxa"/>
                            </w:tblCellMar>
                            <w:tblLook w:val="01E0" w:firstRow="1" w:lastRow="1" w:firstColumn="1" w:lastColumn="1"
                                       w:noHBand="0" w:noVBand="0"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="10196"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="007C1C32" w:rsidRPr="00706FD0" w:rsidTr="007C1C32">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="5000" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="007C1C32" w:rsidRPr="00706FD0" w:rsidRDefault="007C1C32"
                                     w:rsidP="007C1C32">
                                    <w:pPr>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        </w:rPr>
                                        <w:t>'.$standbyContent.'</w:t>
                                    </w:r>

                                </w:p>
                            </w:tc>
                        </w:tr>
                    </w:tbl>';

        if($standbyInstructor != ''){
            $vaecoIds = explode("\r\n", $standbyInstructor);

            $standbyML = '<w:tbl>
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
                                <w:left w:w="57" w:type="dxa"/>
                                <w:right w:w="57" w:type="dxa"/>
                            </w:tblCellMar>
                            <w:tblLook w:val="01E0" w:firstRow="1" w:lastRow="1" w:firstColumn="1" w:lastColumn="1"
                                       w:noHBand="0" w:noVBand="0"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="589"/>
                            <w:gridCol w:w="9607"/>
                        </w:tblGrid>

                    ';
            $i=1;
            foreach ($vaecoIds as $id) {


                $id = trim($id);
                if (strlen($id) == 5) {
                    $id = "VAE" . $id;
                } elseif (strlen($id) == 4) {
                    $id = "VAE0" . $id;
                }
                $id = strtoupper($id);

                $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                if($cus->getId()){
                    $customer = Mage::getModel('customer/customer')->load($cus->getId());
                    $name = $customer->getName();

                    $name = $name .' ('.$id.')';

                    $standbyML .= '<w:tr w:rsidR="007C1C32" w:rsidRPr="00706FD0" w:rsidTr="00BB3745">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="289" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="007C1C32" w:rsidRPr="00BB3745" w:rsidRDefault="00BB3745"
                                     w:rsidP="00BB3745">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        </w:rPr>
                                        <w:t>'.$i.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="4711" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="007C1C32" w:rsidRPr="00706FD0" w:rsidRDefault="007C1C32"
                                     w:rsidP="00E966E4">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$name.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';

                    $i++;
                }





            }
            $standbyML .= '</w:tbl>';
        }

        return $standbyML;

    }

}
