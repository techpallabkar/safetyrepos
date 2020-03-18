<?php
$mainDao = new MainDao();
$eventCategory = $mainDao->getEventCategory();
?>
<div class="mh-widget">
 <h4 class="mh-widget-title"><a href="javascript:void(0)">Upcoming Events</a></h4>
	<ul class="eventtype clearfix">
		<?php 
                $arrData = array(0 => "workshop", 1 => "cm" , 2 => "ppe", 3 => "sd", 4 => "cfa", 5 =>"se");
                foreach( $eventCategory as $keys => $rowData ) {  
                    $showcnt = ($rowData->cn == 0 ) ? '' : '<div class="new">'.$rowData->cn.'</div>';
                    echo '<li><a href="events.php?eventId='.$rowData->id.'" class="'.$arrData[$keys].'">'.$showcnt.'<span>'.$rowData->name.'</span></a></li>';
                } 
               ?>
	</ul>
	
</div>	