<?php
require_once 'lib/phpdocx/CreateDocx.php';
require_once 'app/Mage.php';


umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$finalDir = Mage::getBaseDir('media').DS.'files'.DS;

$finalUrl = Mage::getBaseUrl('media').'/files/';

$docx = new CreateDocxFromTemplate('test.docx');

$html .= "<table border=1 cellspacing=0 cellpadding=0 style='border-collapse:collapse; border:none;'>
        <tr>
            <td width=30 valign=top>
                <p align=center style='margin-top:3.0pt;margin-bottom:6.0pt;'><b>No</b></p>
            </td>
            <td width=90 valign=top>
                <p align=center style='margin-top:3.0pt;margin-bottom:6.0pt;'><b>Date</b></p>
            </td>
            <td width=285 valign=top>
                <p align=center style='margin-top:3.0pt;margin-bottom:6.0pt;'><b>Contents</b></p>
            </td>
            <td width=27 valign=top>
                <p align=center style='margin-top:3.0pt;margin-bottom:6.0pt;'><b>L</b></p>
            </td>
            <td width=36 valign=top>
                <p align=center style='margin-top:3.0pt;margin-bottom:6.0pt;'><b>H</b></p>
            </td>
            <td width=150 valign=top>
                <p align=center style='margin-top:3.0pt;margin-bottom:6.0pt;'><b>Instructors</b></p>
            </td>
            <td width=75 valign=top>
                <p align=center style='margin-top:3.0pt;margin-bottom:6.0pt;'><b>Remark</b></p>
            </td>
        </tr>
        <tr>
            <td valign=middle width=30>
                <p align=center  style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>1</b></p>
            </td>
            <td width=90 valign=middle>
                &nbsp;
            </td>
            <td width=290 valign=middle>
                <p align=left style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>M3 - 99999999</b></p>
            </td>
            <td width=27 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>&nbsp;</b></p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>40</b></p>
            </td>
            <td width=150 valign=middle>
                <p >&nbsp;</p>
            </td>
            <td width=75 valign=middle>
                <p >&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width=30 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>1.1</p>
            </td>
            <td width=90 rowspan=3 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>20/03/2015 - 26/03/2016</p>
            </td>
            <td width=290 valign=middle>
                <p align=left style='margin-top:3.0pt;margin-bottom:3.0pt;'>M3 - 1</p>
            </td>
            <td width=27 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>2</p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>16</p>
            </td>
            <td width=150 rowspan=3 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>Bui Xuan Phong (VAE02907)</p>
            </td>
            <td width=75 valign=middle>
                <p >&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width=30 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>1.2</p>
            </td>
            <td width=290 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>M3 - 2</p>
            </td>
            <td width=27 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>3</p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>16</p>
            </td>
            <td width=75 valign=middle>
                <p >&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width=30 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>1.3</p>
            </td>
            <td width=290 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>M3 - 3 </p>
            </td>
            <td width=27 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>4</p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>8</p>
            </td>
            <td width=75 valign=middle>
                <p >&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width=30 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>2</b></p>
            </td>
            <td width=90 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>&nbsp;</b></p>
            </td>
            <td width=290 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>M5:Kakaka</b></p>
            </td>
            <td width=27 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>&nbsp;</b></p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'><b>24</b></p>
            </td>
            <td width=150 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>&nbsp;</p>
            </td>
            <td width=75 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width=30 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>2.1</p>
            </td>
            <td width=90 rowspan=2 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>20/04/2015 - 26/04/2016</p>
            </td>
            <td width=290 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>M5: Kakaka 1 sdadsadasdasdasdsada</p>
            </td>
            <td width=27 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>5</p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>&nbsp;</p>
            </td>
            <td width=150 rowspan=2 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>Bui Minh Duong (VAE02907)</p>
            </td>
            <td width=75 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width=30 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>2.2</p>
            </td>
            <td width=290 valign=middle>
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>M5: Kakaka  2 sdadsadasdasdasdsada</p>
            </td>
            <td width=27 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>6</p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>&nbsp;</p>
            </td>
            <td width=75 valign=middle>
                <p >&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width=30 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>2.3</p>
            </td>
            <td width=90 rowspan=2 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>20/05/2015 - 26/05/2016</p>
            </td>
            <td width=290 valign=middle >
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>M5: Kakaka  3 sdadsadasdasdasdsada</p>
            </td>
            <td width=27 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>7</p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>&nbsp;</p>
            </td>
            <td width=150 rowspan=2 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>Bui Nguyen Moc Anh (VAE02907)</p>
            </td>
            <td width=75 valign=middle >
                <p >&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width=30 valign=middle >
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>2.4</p>
            </td>
            <td width=290 valign=middle >
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>M5: Kakaka  4 sdadsadasdasdasdsada</p>
            </td>
            <td width=27 valign=middle >
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>8</p>
            </td>
            <td width=36 valign=middle>
                <p align=center style='margin-top:3.0pt;margin-bottom:3.0pt;'>&nbsp;</p>
            </td>
            <td width=75 valign=middle >
                <p style='margin-top:3.0pt;margin-bottom:3.0pt;'>&nbsp;</p>
            </td>
        </tr>
    </table>";

$docx->embedHTML($html);
//$docx->replaceVariableByHTML('html','block',$html);
$res = $docx->createDocx($finalDir.'testdocx');

if($res){
    echo "done";
}