<?php
//http://audiocdn.economist.com/sites/default/files/AudioArchive/2015/20150103/Issue_8919_20150103_The_Economist_Full_edition.zip
$date = new DateTime('2015-01-03');
$j = 8919;

for($i=1; $i<50; $i++){




    echo 'http://audiocdn.economist.com/sites/default/files/AudioArchive/2015/'.$date->format("Ymd").'/Issue_'.$j.'_'.$date->format("Ymd").'_The_Economist_Full_edition.zip<br>';

    $date = $date->add(new DateInterval('P7D'));
    $j += 1;


}



?>
