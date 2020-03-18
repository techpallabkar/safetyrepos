<div class="mh-widget">
 <h4 class="mh-widget-title"><a href="javascript:void(0);">Statistical Data</a></h4>
 </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link href="<?php echo url("assets/css/multifreezer.css"); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo url("assets/js/multifreezer.js"); ?>"></script>
<style>
#freezer-example { width: 100%;margin: 0 auto; }
#freezer-example .table th,#freezer-example .table td { white-space: nowrap; }
#freezer-example .table col { width: 120px; }
</style>
<?php
$mainDao = new MainDao();
$stattisticData = $mainDao->getStatisticMasterData();
$coloarr = array('#2dcf98','#3d99d8','#a67724','#ba70e5');

$mid_date_of_month      =   date('Y-m-15');
$mid_date_next_month    =   date('Y-m-d',strtotime('+1 Month',strtotime($mid_date_of_month)));
$current_date           =   date('Y-m-d');

if( ( $current_date >= $mid_date_of_month ) && ( $current_date < $mid_date_next_month ) )
{
    
    $month_year_1    =  date( 'Y-m', strtotime( "-1 month", mktime( 12, 0, 0, date("m"), 15, date("Y") ) ));
//    $month_year_1    =   date( 'Y-m-d',strtotime( '-1 Month', strtotime($current_date) ) );
} else {

    $month_year_1    =   date( 'Y-m', strtotime( "-2 month", mktime( 12, 0, 0, date("m"), 15, date("Y") ) ));
//    $month_year_1    =   date( 'Y-m',strtotime( '-2 Month', strtotime( $current_date ) ) );
} 
?>

<ul>
    <?php 
    foreach( $stattisticData as $key => $data ) {
        /*************monthly logic start***************/
        $month_year_2   =   $month_year_1;   
        
        $checkdataexist =   $mainDao->checkActivityDataExist($data->id,$month_year_2);
        $month_year_2_back   =  date( 'Y-m',strtotime( '-2 Month', strtotime( $current_date ) ) );
        $month_year          =  ($checkdataexist != 0 ) ? $month_year_2 : $month_year_2_back ;
        
        $month_yearexp       =  explode("-",$month_year);
        $financialyearstart  =  getFinancialYearMonth($month_yearexp[0],$month_yearexp[1],$format="Y");

        $current_month  =   date("Y-m",strtotime($month_year.'-01'));
        $last_year      =   getPrevFinancialYear(($month_yearexp[0]-1),$month_yearexp[1],"Y");
        $ytm_this_year  =   $financialyearstart."~".$current_month;
        /****************monthly logic end************/

        $allStActivityEstb = $mainDao->getStatisticalData($data->id);
         $month = date("F");
    echo '<li style="font-size: 15px;font-weight:600;color:#fff;text-transform:uppercase;width:100%;background:'.$coloarr[$key].';margin-bottom: 10px;text-align: center;">'
            . '<a data-id="'.$data->id.'" data-toggle="modal" data-target="#myModal'.$data->id.'" href="#" style="color:#fff;padding:15px 0;display:block;">'.$data->name.'</a>'
            .' <!-- Modal -->
  <div class="modal fade" id="myModal'.$data->id.'" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">'.$data->name.'</h4>
              <div class="clearfix"></div>
        </div>
        <div class="modal-body">
        <div class="table-responsive">
        <div>
			<div id="freezer-example">
         <table class="table table-bordered table-condensed stat-table table-freeze-multi" style="color:#000;" data-scroll-height="310">';
    if($data->id == 1) {     
    echo '<colgroup>
        <col style="width:170px !important;">
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
	<col>
        <col>
        <col>
    </colgroup>
		<thead><tr class="bg-primary"><th colspan="10">Month : '.date("F, Y",strtotime($month_year.'-01')).' '.$allStActivityEstb[0]->year.'</th></tr>';
       
        echo '
        <tr>
            <th rowspan="3" style="vertical-align:middle;"><b>Activity</b></th>
            <th colspan="9">'.$data->name.'</th>            
        </tr>
        <tr>
            <th colspan="3">Generation</th>
            <th colspan="3">Distribution</th>            
            <th colspan="3">Others</th>            
        </tr>
         <tr>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
        </tr></thead>
        ';
        } else if($data->id == 2) {
        echo ' <colgroup>
		<col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
    </colgroup>
		<thead>
		<tr class="bg-primary"><th colspan="13">Month : '.date("F, Y",strtotime($month_year.'-01')).' '.$allStActivityEstb[0]->year.'</th></tr>
        <tr>
            <th rowspan="4" style="vertical-align:middle;"><b>Establishment</b></th>
            <th colspan="12">'.$data->name.'</th>            
        </tr>
        <tr>
            <th colspan="6">P-SET</th>
            <th colspan="6">C-SET</th>                     
        </tr>
        <tr>
            <th colspan="2">This Month</th>
            <th colspan="2">YTM This Yr.</th>
            <th colspan="2">Last Year</th>
            <th colspan="2">This Month</th>
            <th colspan="2">YTM This Yr.</th>
            <th colspan="2">Last Year</th>           
        </tr>
        <tr>
            <th>AUDIT COUNT</th>
            <th>% AVG</th>
            <th>AUDIT COUNT</th>
            <th>% AVG</th>
            <th>AUDIT COUNT</th>
            <th>% AVG</th>
            <th>AUDIT COUNT</th>
            <th>% AVG</th>
            <th>AUDIT COUNT</th>
            <th>% AVG</th>
            <th>AUDIT COUNT</th>
            <th>% AVG</th>         
        </tr></thead>
        ';
        } else if($data->id == 3 ) {
        echo '<colgroup>
		<col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
    </colgroup>
		<thead>
		<tr class="bg-primary"><th colspan="13">Month : '.date("F, Y",strtotime($month_year.'-01')).' '.$allStActivityEstb[0]->year.'</th></tr>
		<tr>
            <th rowspan="3" style="vertical-align:middle;"><b>Establishment</b></th>
            <th colspan="12">'.$data->name.'</th>            
            </tr>
        <tr>
            <th colspan="3">FAC</th>
            <th colspan="3">LWDC</th>            
            <th colspan="3">NEAR MISS</th>            
            <th colspan="3">FATAL</th>            
        </tr>
        <tr>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
        </tr></thead>';
        } else if($data->id == 4 ) {
        echo '<colgroup><col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
    </colgroup>
		<thead>
		<tr class="bg-primary"><th colspan="13">Month : '.date("F, Y",strtotime($month_year.'-01')).' '.$allStActivityEstb[0]->year.'</th></tr>
		<tr>
            <th rowspan="3" style="vertical-align:middle;"><b>Establishment</b></th>
            <th colspan="12">'.$data->name.'</th>            
            </tr>
        <tr>
            <th colspan="3">FAC</th>
            <th colspan="3">LWDC</th>            
            <th colspan="3">NEAR MISS</th>            
            <th colspan="3">FATAL</th>            
        </tr>
        <tr>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
        </tr></thead>';
        }         
        echo '<tbody style="color:#000;">';     
        
        $tablename = array("5" => "audit_view","1" => "activity_view","2" => "activity_view","3" => "training_ws_view" ,"7" => "ppe_audit_view","8" => "safety_observation_view" );
        $colname = array("5" => "date_of_audit","1" => "activity_date","2" => "activity_date","3" => "from_date" ,"7" => "date_of_audit","8" => "" );
         if(!empty($allStActivityEstb)) {
            foreach( $allStActivityEstb as $row ) {
                $general_activity_array = array();
                    
                    $cls = ($data->id == 2) ? "display:none;" : "";
                    
                if( $data->id == 1 ) {
                    $generation_curr_month      =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],1,'249-2',$current_month);
                    $generation_ytm_thisyr      =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],2,'249-2',$ytm_this_year);
                    $generation_last_yr         =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],3,'249-2',$last_year);
                    
                    $distribution_curr_month    =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],1,'249-3',$current_month);
                    $distribution_ytm_thisyr    =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],2,'249-3',$ytm_this_year);
                    $distribution_last_yr       =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],3,'249-3',$last_year);
                    
                    $others_curr_month          =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],1,'249-4',$current_month);
                    $others_ytm_thisyr          =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],2,'249-4',$ytm_this_year);
                    $others_last_yr             =   $mainDao->getGeneralData($row->division_id,$tablename[$row->division_id],$colname[$row->division_id],3,'249-4',$last_year);
                    
                if($row->division_id == 3 || $row->division_id == 8){
                    echo '<tr>
                    <td>'.$row->name.'</td>
                    <td>'.$generation_curr_month.'</td>
                    <td>'.$generation_ytm_thisyr.'</td>
                    <td>'.$generation_last_yr.'</td>
                    <td>'.$distribution_curr_month.'</td>
                    <td>'.$distribution_ytm_thisyr.'</td>
                    <td>'.$distribution_last_yr.'</td>
                    <td style="'.$cls.'">'.$others_curr_month.'</td>
                    <td style="'.$cls.'">'.$others_ytm_thisyr.'</td>
                    <td style="'.$cls.'">'.$others_last_yr.'</td>
                    </tr>';
                }else{
                    echo '<tr>
                    <td>'.$row->name.'</td>
                    <td>'.count($generation_curr_month).'</td>
                    <td>'.count($generation_ytm_thisyr).'</td>
                    <td>'.count($generation_last_yr).'</td>
                    <td>'.count($distribution_curr_month).'</td>
                    <td>'.count($distribution_ytm_thisyr).'</td>
                    <td>'.count($distribution_last_yr).'</td>
                    <td style="'.$cls.'">'.count($others_curr_month).'</td>
                    <td style="'.$cls.'">'.count($others_ytm_thisyr).'</td>
                    <td style="'.$cls.'">'.count($others_last_yr).'</td>
                    </tr>';
                }
         }
                
                if( $data->id == 2 ) {
                    
                    $audit_count_pset_current_month    =   $mainDao->getAuditScoreData($row->division_id,1,'P-SET',$current_month);
                    $audit_count_pset_ytm_this_year    =   $mainDao->getAuditScoreData($row->division_id,2,'P-SET',$ytm_this_year);
                    $audit_count_pset_last_year        =   $mainDao->getAuditScoreData($row->division_id,3,'P-SET',$last_year);
 
                    $audit_count_cset_current_month    =   $mainDao->getAuditScoreData($row->division_id,1,'C-SET',$current_month);
                    $audit_count_cset_ytm_this_year    =   $mainDao->getAuditScoreData($row->division_id,2,'C-SET',$ytm_this_year);
                    $audit_count_cset_last_year        =   $mainDao->getAuditScoreData($row->division_id,3,'C-SET',$last_year);

                   echo '<tr>'
                    . '<td>'.$row->name.'</td>'
                    . '<td>'.$audit_count_pset_current_month['totaldiv'].'</td>'
                    . '<td>'.round($audit_count_pset_current_month['score'],2).'</td>'
                           
                    . '<td>'.$audit_count_pset_ytm_this_year['totaldiv'].'</td>'
                    . '<td>'.round($audit_count_pset_ytm_this_year['score'],2).'</td>'
                           
                    . '<td>'.$audit_count_pset_last_year['totaldiv'].'</td>'
                    . '<td>'.round($audit_count_pset_last_year['score'],2).'</td>'
                           
                    . '<td>'.$audit_count_cset_current_month['totaldiv'].'</td>'
                    . '<td>'.round($audit_count_cset_current_month['score'],2).'</td>'
                           
                    . '<td>'.$audit_count_cset_ytm_this_year['totaldiv'].'</td>'
                    . '<td>'.round($audit_count_cset_ytm_this_year['score'],2).'</td>'
                           
                    . '<td>'.$audit_count_cset_last_year['totaldiv'].'</td>'
                    . '<td>'.round($audit_count_cset_last_year['score'],2).'</td>'
                    . '</tr>'; 
                }
                if( $data->id == 3 ) {
                    $incident_category_fac_current_month_PSET         =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,1,2,$current_month);
                    $incident_category_fac_ytm_this_year_PSET         =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,2,2,$ytm_this_year);
                    $incident_category_fac_last_year_PSET             =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,3,2,$last_year);
                         
                    $incident_category_lwdc_current_month_PSET        =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,1,3,$current_month);
                    $incident_category_lwdc_ytm_this_year_PSET        =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,2,3,$ytm_this_year);
                    $incident_category_lwdc_last_year_PSET            =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,3,3,$last_year);
                    
                    $incident_category_nearmiss_current_month_PSET    =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,1,1,$current_month);
                    $incident_category_nearmiss_ytm_this_year_PSET    =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,2,1,$ytm_this_year);
                    $incident_category_nearmiss_last_year_PSET        =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,3,1,$last_year);
                    
                    $incident_category_fatal_current_month_PSET       =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,1,4,$current_month);
                    $incident_category_fatal_ytm_this_year_PSET       =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,2,4,$ytm_this_year);
                    $incident_category_fatal_last_year_PSET           =   $mainDao->getIncidentCategoryData('P-SET',$row->division_id,3,4,$last_year);
             
                    
                   echo '<tr>'
                    . '<td>'.$row->name.'</td>'
                    . '<td>'.count($incident_category_fac_current_month_PSET).'</td>'
                    . '<td>'.count($incident_category_fac_ytm_this_year_PSET).'</td>'
                    . '<td>'.count($incident_category_fac_last_year_PSET).'</td>'
                           
                    . '<td>'.count($incident_category_lwdc_current_month_PSET).'</td>'
                    . '<td>'.count($incident_category_lwdc_ytm_this_year_PSET).'</td>'
                    . '<td>'.count($incident_category_lwdc_last_year_PSET).'</td>'
                           
                    . '<td>'.count($incident_category_nearmiss_current_month_PSET).'</td>'
                    . '<td>'.count($incident_category_nearmiss_ytm_this_year_PSET).'</td>'
                    . '<td>'.count($incident_category_nearmiss_last_year_PSET).'</td>'
                           
                    . '<td>'.count($incident_category_fatal_current_month_PSET).'</td>'
                    . '<td>'.count($incident_category_fatal_ytm_this_year_PSET).'</td>'
                    . '<td>'.count($incident_category_fatal_last_year_PSET).'</td>'
                    . '</tr>'; 
                   

                   
                }
                if( $data->id == 4 ) {
                    $incident_category_fac_current_month_CSET         =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,1,2,$current_month);
                    $incident_category_fac_ytm_this_year_CSET         =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,2,2,$ytm_this_year);
                    $incident_category_fac_last_year_CSET             =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,3,2,$last_year);
                         
                    $incident_category_lwdc_current_month_CSET        =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,1,3,$current_month);
                    $incident_category_lwdc_ytm_this_year_CSET        =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,2,3,$ytm_this_year);
                    $incident_category_lwdc_last_year_CSET            =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,3,3,$last_year);
                    
                    $incident_category_nearmiss_current_month_CSET    =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,1,1,$current_month);
                    $incident_category_nearmiss_ytm_this_year_CSET    =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,2,1,$ytm_this_year);
                    $incident_category_nearmiss_last_year_CSET        =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,3,1,$last_year);
                    
                    $incident_category_fatal_current_month_CSET       =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,1,4,$current_month);
                    $incident_category_fatal_ytm_this_year_CSET       =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,2,4,$ytm_this_year);
                    $incident_category_fatal_last_year_CSET           =   $mainDao->getIncidentCategoryData('C-SET',$row->division_id,3,4,$last_year);
             
                    
                   echo '<tr>'
                    . '<td>'.$row->name.'</td>'
                    . '<td>'.count($incident_category_fac_current_month_CSET).'</td>'
                    . '<td>'.count($incident_category_fac_ytm_this_year_CSET).'</td>'
                    . '<td>'.count($incident_category_fac_last_year_CSET).'</td>'
                           
                    . '<td>'.count($incident_category_lwdc_current_month_CSET).'</td>'
                    . '<td>'.count($incident_category_lwdc_ytm_this_year_CSET).'</td>'
                    . '<td>'.count($incident_category_lwdc_last_year_CSET).'</td>'
                           
                    . '<td>'.count($incident_category_nearmiss_current_month_CSET).'</td>'
                    . '<td>'.count($incident_category_nearmiss_ytm_this_year_CSET).'</td>'
                    . '<td>'.count($incident_category_nearmiss_last_year_CSET).'</td>'
                           
                    . '<td>'.count($incident_category_fatal_current_month_CSET).'</td>'
                    . '<td>'.count($incident_category_fatal_ytm_this_year_CSET).'</td>'
                    . '<td>'.count($incident_category_fatal_last_year_CSET).'</td>'
                    . '</tr>'; 
                   
                }
            } 
            if($data->id == 3 ) {
                $fatalcount= $mainDao->getFatalCount($current_month);
                if( $fatalcount > 0 ) {
                    echo '<tr ><td style="color:#CA0000;font-weight:bold !important;">INCIDENT FATAL</td><td align="left" style="color:#CA0000;font-weight:bold !important;" colspan="9">'.$fatalcount.'</td></tr>';
                }
            }
        }
        echo '</tbody>';
        echo '</table></div></div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>'
. '</li>';
    }
    ?>
</ul>

<script src="<?php echo url("assets/js/bootstrap.min.js") ?>"></script>

