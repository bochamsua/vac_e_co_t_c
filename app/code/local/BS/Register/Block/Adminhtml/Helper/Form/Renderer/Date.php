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
 * fieldset element renderer
 * @category   BS
 * @package    BS_Trainee
 * @author      Bui Phong
 */
class BS_Register_Block_Adminhtml_Helper_Form_Renderer_Date extends Varien_Data_Form_Element_Date
{

    public function getElementHtml()
    {
        
        $this->addClass('input-text');
        
        $startDate = $this->getFrom();
        $startDate = explode("/", $startDate);
        $startDay = (int)$startDate[0];
        $startMonth = (int)$startDate[1] - 1;//because we compare the index of month in year, not the real month, Jan is 0, Dec is 11
        $startYear = (int)$startDate[2];

        $finishDate = $this->getTo();
        $finishDate = explode("/", $finishDate);
        $finishDay = (int)$finishDate[0];
        $finishMonth = (int)$finishDate[1] - 1;
        $finishYear = (int)$finishDate[2];

        $html = sprintf(
            '<input name="%s" id="%s" value="%s" %s style="width:110px !important;" />'
            .' <img src="%s" alt="" class="v-middle" id="%s_trig" title="%s" style="%s" />',
            $this->getName(), $this->getHtmlId(), $this->_escape($this->getValue()), $this->serialize($this->getHtmlAttributes()),
            $this->getImage(), $this->getHtmlId(), 'Select Date', ($this->getDisabled() ? 'display:none;' : '')
        );
        $outputFormat = $this->getFormat();
        if (empty($outputFormat)) {
            throw new Exception('Output format is not specified. Please, specify "format" key in constructor, or set it using setFormat().');
        }
        $displayFormat = Varien_Date::convertZendToStrFtime($outputFormat, true, (bool)$this->getTime());

        $html .= '
            <script type="text/javascript">
            //<![CDATA[
                Calendar.setup({
                    inputField: "'.$this->getHtmlId().'",
                    ifFormat: "'.$displayFormat.'",
                    showsTime: false,
                    button: "'.$this->getHtmlId().'_trig",
                    align: "Bl",
                    //date: "Apr 19, 2016",
                    singleClick: true,
                    disableFunc: function(date)  {
                        if(date.getFullYear()   <   '.$startYear.')  { return true; }
                        if(date.getFullYear()   ==  '.$startYear.')  { if(date.getMonth()    <   '.$startMonth.') { return true; } }
                        if(date.getMonth()      ==  '.$startMonth.')     { if(date.getDate()     <   '.$startDay.')  { return true; } }
                        
                        if(date.getFullYear()   >  '.$finishYear.')  { return true; }
                        if(date.getFullYear()   ==  '.$finishYear.')  { if(date.getMonth()    >   '.$finishMonth.') { return true; } }
                        if(date.getMonth()      ==  '.$finishMonth.')     { if(date.getDate()     >   '.$finishDay.')  { return true; } }
                        
                    },
                });
            //]]>
            </script>';

        $html .= $this->getAfterElementHtml();

        return $html;
    }


}
