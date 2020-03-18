<?php
/* MIS Report No. : 13
 * Name         :   Spot Light Incident Statistics  
 * Controller   :   spotLightIncidentStatistics()
 * Dao          :   getReportedAccidentStatistics,
 * Created By anima
 */

if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$REPOTED_ACC_STAT_PSET = $beanUi->get_view_data("REPOTED_ACC_STAT_PSET");
$REPOTED_ACC_STAT_CSET = $beanUi->get_view_data("REPOTED_ACC_STAT_CSET");
$REPOTED_ACC_STAT_PCSET = $beanUi->get_view_data("REPOTED_ACC_STAT_PCSET");
$REPOTED_ACC_STAT_OTHERS = $beanUi->get_view_data("REPOTED_ACC_STAT_OTHERS");
$month_year = $beanUi->get_view_data("month_year");
$getAllPSETData = $beanUi->get_view_data("getAllPSETData");//show($getAllPSETData);
$getAllCSETData = $beanUi->get_view_data("getAllCSETData");
$getAllPCSETData = $beanUi->get_view_data("getAllPCSETData");
$getAllOTHERSData = $beanUi->get_view_data("getAllOTHERSData");
$incident_category = $beanUi->get_view_data("incident_category");
$incident_type = $beanUi->get_view_data("incident_type");
//show($getAllOTHERSData);
$getDate = $beanUi->get_view_data("getDate");
$controller->get_header();
$site_root_url = dirname(url());
//$month_year_formatted = date("M ' Y", strtotime("01-" . $month_year));
$month_year_formatted = date("M'y", strtotime("01-" . $month_year));

//date("M-y",strtotime('01-'.$_REQUEST['month_year']))

@$previous_financial_year = $getDate["PREV_Y1"];
$exp_prev_fy_year = explode(",", $previous_financial_year);
@$prevfyyear = date("Y", strtotime($exp_prev_fy_year[0] . "-01")) . '-' . date("y", strtotime($exp_prev_fy_year[1] . "-01"));
$pre_fin_y_exp = explode("-", $prevfyyear);
$preFinYExpF = substr($pre_fin_y_exp[0],2);
$preFinYExpL = ($pre_fin_y_exp[1]);
$PreviousFY = $preFinYExpF.'-'.$preFinYExpL;


@$present_financial_year = $getDate["CURR_Y"];
$exp_pres_fy_year = explode(",", $present_financial_year);
@$presfyyear = date("Y", strtotime($exp_pres_fy_year[0] . "-01")) . '-' . date("y", strtotime($exp_pres_fy_year[1] . "-01"));
$cur_fin_y_exp = explode("-", $presfyyear);
$curFinYExpF = substr($cur_fin_y_exp[0],2);
$curFinYExpL = ($cur_fin_y_exp[1]);
$CurrentFY = $curFinYExpF.'-'.$curFinYExpL;

?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
    $(document).ready(function () {
    <?php
    if(!empty($incident_category)){
    foreach ($incident_category as $key => $value) { ?>

          get_others_except_syscontrol('PSET',<?php echo $value->id; ?>);
          get_others_except_syscontrol('CSET',<?php echo $value->id; ?>);


            get_total_value('PSET',<?php echo $value->id; ?>);
            get_total_value('CSET',<?php echo $value->id; ?>);
    <?php }} ?>
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">13. Total Incident Statistics (Spot Light) </h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>
    <form action="" method="post" id="mdform" enctype="multipart/form-data" >
        <div class="holder">
            <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
            <input type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo $month_year; ?>" style="width:250px;"  required/>
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="spotLightIncidentStatistics.php" class="btn btn-danger btn-sm">Reset</a>                 
        </div>
        <hr class="no-margin"/>
    </form>
    <!--REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES--> 
    <?php if (!empty($getAllPSETData)) { ?>
    <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
        Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
    <?php } ?>
    <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">
        <?php if (!empty($getAllPSETData)) {  ?>
            <table border="1" class="table table-bordered table-condensed table-responsive totaldata" style="font-size:13px;font-weight:bold;" >
                <thead class="bg-primary">
                    <tr>
                        <th rowspan="3">SL</th>
                        <th rowspan="3"> DEPT. </th>
                        <th rowspan="3"> Incident Type (#) </th>
                        <!--<th  colspan="14"> REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES UPTO MONTH : <?php echo strtoupper(date("F", strtotime('01-' . @$month_year))); ?></th>-->
                        <th  colspan="14"> STATISTICS OF REPORTED INJURIES AND NEAR MISS CASES FOR PERMANENT EMPLOYEES</th>
                    </tr>
                    <tr>
                        <?php
                        foreach ($incident_category as $k => $val) {
                            if($val->id != 1) {
                            echo '<th colspan="3" >'.$val->name.'</th>';
                            }
                        } 
                        ?>
                        <th colspan="2">TOTAL</th>
                         <?php
                        foreach ($incident_category as $k => $val) {
                            if($val->id == 1) {
                            echo '<th colspan="3" >'.$val->name.'</th>';
                            }
                        } 
                        ?>
                    </tr>
                    <tr>
                    <?php 
                    for ($i = 0; $i <= 4; $i++) {
                        if($i != 3) {
                        echo "<th>".strtoupper($month_year_formatted)."</th>";
                        } 
                        echo "<th> YTM ' ".$CurrentFY."</th><th> YTM ' ".$PreviousFY."</th>";
                       
                    } 
                    ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total1 = $total2 = $total3 = 0;
                    $datastorearr = array();
                    $slno=0;
                    $total_cm1 = 0;
                    $total_cyy1 = 0;
                    $total_pyy1 = 0;
                    $total_cm2 = 0;
                    $total_cyy2 = 0;
                    $total_pyy2 = 0;
                    $total_cm3 = 0;
                    $total_cyy3 = 0;
                    $total_pyy3 = 0;
                    $total_cm4 = 0;
                    $total_cyy4 = 0;
                    $total_pyy4 = 0;
                    $total_cm5 = 0;
                    $total_cyy5 = 0;
                    $total_pyy5 = 0;
                    $total_cm6 = 0;
                    $total_cyy6 = 0;
                    $total_pyy6 = 0;
                    $total_cm7 = 0;
                    $total_cyy7 = 0;
                    $total_pyy7 = 0;
                    $total_cm8 = 0;
                    $total_cyy8 = 0;
                    $total_pyy8 = 0;                    
                    foreach ($getAllPSETData as $key => $rowdata) {
                       foreach($incident_type as $o => $p){                           
                    $tid = $rowdata[$p]["id"];
//                    if($tid != 10) {
                         (($p == 1) ? $slno++ : '');
                        $total_current_year = $total_prev_ytm = $total_current_ytm = 0;
                        $color ="";
                        if( $key < 3) {
                            $color = "#FFC000";
                        } else if($key > 1 && $key < 14) {
                            $color= "#DEEBF6";
                        } else if($key > 14 && $key < 19) {
                            $color= "#92D050";
                        } else {
                            $color= "#E1EED9";
                        }
                        $td_hidep = '<td rowspan="2" bgcolor="'.$color.'" align="center" style="border:1px solid #000;">' . ($slno).  '.</td><td rowspan="2" bgcolor="'.$color.'" style="border:1px solid #000;">' . ($rowdata[$p]["name"]) . '</td>';
                        echo '<tr>';
                        echo (($p == 1) ? $td_hidep : '');
                        echo '<td bgcolor="'.$color.'" style="border:1px solid #000;">' . (($p == 1) ? 'E' : 'M') . '</td>';
                        foreach ($rowdata[$p]["INC"] as $k => $val) {
                            if ($k != 1) {                            
                                if(($p == 1) && ($k == 2)){                            
                                    $total_cm1  += $val["CURRENT_MONTH"];
                                    $total_cyy1 +=  $val["CURRENT_YEAR_YTM"];
                                    $total_pyy1 +=  $val["PREVIOUS_YEAR_YTM"];                            
                                }elseif(($p == 1) && ($k == 3)){
                                    $total_cm2  += $val["CURRENT_MONTH"];
                                    $total_cyy2 +=  $val["CURRENT_YEAR_YTM"];
                                    $total_pyy2 +=  $val["PREVIOUS_YEAR_YTM"];
                                }elseif(($p == 1) && ($k == 4)){
                                    $total_cm3  += $val["CURRENT_MONTH"];
                                    $total_cyy3 +=  $val["CURRENT_YEAR_YTM"];
                                    $total_pyy3 +=  $val["PREVIOUS_YEAR_YTM"];
                                }elseif(($p == 2) && ($k == 2)){                            
                                    $total_cm4  += $val["CURRENT_MONTH"];
                                    $total_cyy4 +=  $val["CURRENT_YEAR_YTM"];
                                    $total_pyy4 +=  $val["PREVIOUS_YEAR_YTM"];                            
                                }elseif(($p == 2) && ($k == 3)){
                                    $total_cm5  += $val["CURRENT_MONTH"];
                                    $total_cyy5 +=  $val["CURRENT_YEAR_YTM"];
                                    $total_pyy5 +=  $val["PREVIOUS_YEAR_YTM"];
                                }elseif(($p == 2) && ($k == 4)){
                                    $total_cm6  += $val["CURRENT_MONTH"];
                                    $total_cyy6 +=  $val["CURRENT_YEAR_YTM"];
                                    $total_pyy6 +=  $val["PREVIOUS_YEAR_YTM"];
                                }                                
                            $td_hidep_ppyr = '<td rowspan="2" bgcolor="'.$color.'" class="PSETPREVYTMDEDUCT_'.$k.'_'.$tid.' PSETPREVYTM_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["PREVIOUS_YEAR_YTM"]) . '</td>';   
                            echo '<td bgcolor="'.$color.'" class="PSETCMNTHDEDUCT_'.$k.' PSETCMNTH_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["CURRENT_MONTH"]) . '</td>'
                            . '<td bgcolor="'.$color.'" class="PSETCURYTMDEDUCT_'.$k.' PSETCURYTM_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["CURRENT_YEAR_YTM"]) . '</td>';
                            echo (($p == 1) ? $td_hidep_ppyr : '');                                
                                $total_current_year +=  $val["CURRENT_MONTH"];
                                $total_prev_ytm     +=  $val["PREVIOUS_YEAR_YTM"];
                                $total_current_ytm  +=  $val["CURRENT_YEAR_YTM"];
                            }
                        }
                        ?>
                    <script>
                        $(document).ready(function () {
                            get_side_total_value('PSET',<?php echo $tid; ?>);
                        });
                    </script>
                    <?php
                    $td_hidep_ttpyr2='<td rowspan="2" bgcolor="'.$color.'" align="center" style="border:1px solid #000;" id="PSETPREVYTMTOT_'.$tid.'"></td>';
                        echo '<td bgcolor="'.$color.'" align="center" style="border:1px solid #000;" id="">'.$total_current_year.'</td>';
                        echo (($p == 1) ? $td_hidep_ttpyr2 : '');                       
                         foreach ($rowdata[$p]["INC"] as $ks => $val1) {
                            if ($ks == 1) {                                
                                if($p == 1){                                
                                    $total_cm7  += $val1["CURRENT_MONTH"];
                                    $total_cyy7 +=  $val1["CURRENT_YEAR_YTM"];
                                    $total_pyy7 +=  $val1["PREVIOUS_YEAR_YTM"];
                                }else{                                    
                                    $total_cm8  += $val1["CURRENT_MONTH"];
                                    $total_cyy8 +=  $val1["CURRENT_YEAR_YTM"];
                                    $total_pyy8 +=  $val1["PREVIOUS_YEAR_YTM"];
                                }
                                $td_hidep_nmpyr1='<td rowspan="2" bgcolor="'.$color.'" class="PSETPREVYTMDEDUCT_'.$ks.'_'.$tid.' PSETPREVYTM_' . $ks . '" align="center" style="border:1px solid #000;">' . ($val1["PREVIOUS_YEAR_YTM"]). '</td>';    
                                echo '<td bgcolor="'.$color.'" class="PSETCMNTHDEDUCT_'.$ks.' PSETCMNTH_' . $ks . '" align="center" style="border:1px solid #000;">'.$val1["CURRENT_MONTH"]. '</td>'
                            . '<td bgcolor="'.$color.'" class="PSETCURYTMDEDUCT_'.$ks.' PSETCURYTM_' . $ks . '" align="center" style="border:1px solid #000;">' . ($val1["CURRENT_YEAR_YTM"]) . '</td>';
                                echo (($p == 1) ? $td_hidep_nmpyr1 : '');
                            }
                        }
                        echo '</tr>';
                        $total1 += $total_current_year;
                        $total2 += $total_prev_ytm;
                        $total3 += $total_current_ytm;
//                    }                    
                    } }
                    ?>
                    <tr  style="font-size: 16px;background:#FFFF00;"> 
                        <th style="border-color:#000;" colspan="2" rowspan="2">TOTAL </th>
                        <th style="border-color:#000;"> E</th>

                        <th style="border-color:#000;" id=""><?php echo $total_cm1; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy1; ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo $total_pyy1; ?></th>
                        

                        <th style="border-color:#000;" id=""><?php echo $total_cm2; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy2; ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo $total_pyy2; ?></th>
                        

                        <th style="border-color:#000;" id=""><?php echo $total_cm3; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy3; ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo $total_pyy3; ?></th>
                        

                        <!--<th style="border-color:#000;" id="PSET_TOT_P1"></th>-->
                        <th style="border-color:#000;" id=""><?php echo ($total_cyy1+$total_cyy2+$total_cyy3); ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo ($total_pyy1+$total_pyy2+$total_pyy3); ?></th>
                        
                        
                        <th style="border-color:#000;" id=""><?php echo $total_cm7; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy7; ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo $total_pyy7; ?></th>
                        
                    </tr>
                    <tr  style="font-size: 16px;background:#FFFF00;"> 
                        <!--<th style="border-color:#000;" colspan="2">TOTAL </th>-->
                        <th style="border-color:#000;">M </th>

                        <th style="border-color:#000;" id=""><?php echo $total_cm4; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy4; ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        

                        <th style="border-color:#000;" id=""><?php echo $total_cm5; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy5; ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        

                        <th style="border-color:#000;" id=""><?php echo $total_cm6; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy6; ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        

                        <!--<th style="border-color:#000;" id="PSET_TOT_P1"></th>-->
                        <th style="border-color:#000;" id=""><?php echo ($total_cyy4+$total_cyy5+$total_cyy6); ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        
                        
                        <th style="border-color:#000;" id=""><?php echo $total_cm8; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy8; ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        
                    </tr>
                    <tr  style="font-size: 16px;background:#FFFF00;"> 
                        <th style="border-color:#000;" colspan="2">GRAND TOTAL </th>
                        <th style="border-color:#000;"> </th>

                        <th style="border-color:#000;" id="PSETCMNTH_2"></th>
                        <th style="border-color:#000;" id="PSETCURYTM_2"></th>
                        <th style="border-color:#000;" id="PSETPREVYTM_2"></th>
                        

                        <th style="border-color:#000;" id="PSETCMNTH_3"></th>
                        <th style="border-color:#000;" id="PSETCURYTM_3"></th>
                        <th style="border-color:#000;" id="PSETPREVYTM_3"></th>
                        

                        <th style="border-color:#000;" id="PSETCMNTH_4"></th>
                        <th style="border-color:#000;" id="PSETCURYTM_4"></th>
                        <th style="border-color:#000;" id="PSETPREVYTM_4"></th>
                        

                        <!--<th style="border-color:#000;" id="PSET_TOT_P1"></th>-->
                        <th style="border-color:#000;" id="PSET_TOT_CUR"></th>
                        <th style="border-color:#000;" id="PSET_TOT_PREV"></th>
                        
                        
                        <th style="border-color:#000;" id="PSETCMNTH_1"></th>
                        <th style="border-color:#000;" id="PSETCURYTM_1"></th>
                        <th style="border-color:#000;" id="PSETPREVYTM_1"></th>
                        
                    </tr>
                </tbody>
            </table>
            <?php } ?>


        <!--REPORTED ACCIDENT STATISTICS OF CONTRACTOR EMPLOYEES--> 
        <?php if (!empty($getAllCSETData)) {  ?>
            <table border="1" class="table table-bordered table-condensed table-responsive totaldata" style="font-size:13px;font-weight:bold;" >
                <thead class="bg-primary">
                    <tr>
                        <th rowspan="3">SL</th>
                        <th rowspan="3"> DEPT. </th>
                        <th rowspan="3"> Incident Type (#) </th>
                        <!--<th  colspan="14"> REPORTED ACCIDENT STATISTICS OF CONTRACTUAL EMPLOYEES UPTO MONTH : <?php echo strtoupper(date("F", strtotime('01-' . @$month_year))); ?></th>-->
                        <th  colspan="14"> STATISTICS OF REPORTED INJURIES AND NEAR MISS CASES FOR CONTRACTOR PERSONNEL</th>
                        <th style="background: #fff;"></th>
                        <th rowspan="2" colspan="2">Incident Free Days (P&C )</th>
                    </tr>
                    <tr>
                        <?php
                        foreach ($incident_category as $k => $val) {
                            if($val->id != 1) {
                            echo '<th colspan="3" >'.$val->name.'</th>';
                            }
                        } 
                        ?>
                        <th colspan="2">TOTAL</th>
                        <?php
                        foreach ($incident_category as $k => $val) {
                            if($val->id == 1) {
                            echo '<th colspan="3" >'.$val->name.'</th>';
                            }
                        } 
                        ?>
                    </tr>
                    <tr>
                    <?php 
                     for ($i = 0; $i <= 4; $i++) {
                        if($i != 3) {
                            echo '<th>'.strtoupper($month_year_formatted).'</th>';
                        }
                        
                        echo '<th> YTM '.$CurrentFY.'</th><th> YTM '.$PreviousFY.'</th>';
                    } 
                    ?>
                        <th style="background: #fff;"></th>
                        <th>TOTAL</th>
                        <th>FATAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     $total1 = $total2 = $total3 = 0;
                    $cslno =0;
                    $total_cm11 = 0;
                    $total_cyy11 = 0;
                    $total_pyy11 = 0;
                    $total_cm22 = 0;
                    $total_cyy22 = 0;
                    $total_pyy22 = 0;
                    $total_cm33 = 0;
                    $total_cyy33 = 0;
                    $total_pyy33 = 0;
                    $total_cm44 = 0;
                    $total_cyy44 = 0;
                    $total_pyy44 = 0;
                    $total_cm55 = 0;
                    $total_cyy55 = 0;
                    $total_pyy55 = 0;
                    $total_cm66 = 0;
                    $total_cyy66 = 0;
                    $total_pyy66 = 0;
                    $total_cm77 = 0;
                    $total_cyy77 = 0;
                    $total_pyy77 = 0;
                    $total_cm88 = 0;
                    $total_cyy88 = 0;
                    $total_pyy88 = 0;
                     foreach ($getAllCSETData as $key => $rowdata) {
                       foreach($incident_type as $m => $n){
                         $tid = $rowdata[$n]["id"];
                          $total_current_year = $total_prev_ytm = $total_current_ytm = 0;
                          if( $key < 3) {
                            $color = "#FFC000";
                            } else if($key > 1 && $key < 14) {
                                $color= "#DEEBF6";
                            } else if($key > 14 && $key < 19) {
                                $color= "#92D050";
                            } else {
                                $color= "#E1EED9";
                            }
                          
                        (($n == 1) ? $cslno++ : '');
                        $td_hidec = '<td rowspan="2" bgcolor="' . $color . '" align="center" style="border:1px solid #000;">' . $cslno . '.</td><td rowspan="2" bgcolor="' . $color . '" style="border:1px solid #000;">' . ($rowdata[$n]["name"]) . '</td>';
//                        echo '<tr class="tree_'.$tid.'">';
                        echo (($n == 1) ? $td_hidec : '');

                        echo '<td bgcolor="' . $color . '" style="border:1px solid #000;">' . (($n == 1) ? 'E' : 'M') . '</td>';

                        foreach ($rowdata[$n]["INC"] as $k => $val) {
                            if ($k != 1) {
                                
                                if(($n == 1) && ($k == 2)){
                            
                                $total_cm11  += $val["CURRENT_MONTH"];
                                $total_cyy11 +=  $val["CURRENT_YEAR_YTM"];
                                $total_pyy11 +=  $val["PREVIOUS_YEAR_YTM"];
                            
                            }else if(($n == 1) && ($k == 3)){

                                $total_cm22  += $val["CURRENT_MONTH"];
                                $total_cyy22 +=  $val["CURRENT_YEAR_YTM"];
                                $total_pyy22 +=  $val["PREVIOUS_YEAR_YTM"];

                            }else if(($n == 1) && ($k == 4)){

                                $total_cm33  += $val["CURRENT_MONTH"];
                                $total_cyy33 +=  $val["CURRENT_YEAR_YTM"];
                                $total_pyy33 +=  $val["PREVIOUS_YEAR_YTM"];

                            }
                            
                            if(($n == 2) && ($k == 2)){
                            
                                $total_cm44  += $val["CURRENT_MONTH"];
                                $total_cyy44 +=  $val["CURRENT_YEAR_YTM"];
                                $total_pyy44 +=  $val["PREVIOUS_YEAR_YTM"];
                            
                            }else if(($n == 2) && ($k == 3)){

                                $total_cm55  += $val["CURRENT_MONTH"];
                                $total_cyy55 +=  $val["CURRENT_YEAR_YTM"];
                                $total_pyy55 +=  $val["PREVIOUS_YEAR_YTM"];

                            }else if(($n == 2) && ($k == 4)){

                                $total_cm66  += $val["CURRENT_MONTH"];
                                $total_cyy66 +=  $val["CURRENT_YEAR_YTM"];
                                $total_pyy66 +=  $val["PREVIOUS_YEAR_YTM"];

                            }
                                
                            $td_hidec_pyr =    '<td rowspan="2" bgcolor="' . $color . '" class="CSETPREVYTMDEDUCT_'.$k.'_'.$tid.' CSETPREVYTM_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["PREVIOUS_YEAR_YTM"]) . '</td>';
                            echo '<td bgcolor="' . $color . '" class="CSETCMNTHDEDUCT_'.$k.'_'.$tid.' CSETCMNTH_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["CURRENT_MONTH"]) . '</td>'
                                . '<td bgcolor="' . $color . '" class="CSETCURYTMDEDUCT_'.$k.'_'.$tid.' CSETCURYTM_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["CURRENT_YEAR_YTM"]) . '</td>';
//                                . '<td bgcolor="' . $color . '" class="CSETPREVYTMDEDUCT_'.$k.'_'.$tid.' CSETPREVYTM_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["PREVIOUS_YEAR_YTM"]) . '</td>';
                            echo (($n == 1) ? $td_hidec_pyr : '');
                            
                                $total_current_year +=  $val["CURRENT_MONTH"];
                                $total_prev_ytm     +=  $val["PREVIOUS_YEAR_YTM"];
                                $total_current_ytm  +=  $val["CURRENT_YEAR_YTM"];
                            }
                        }
                        ?>
                    <script>
                        $(document).ready(function () {
                             get_side_total_value('CSET',<?php echo $tid; ?>);
                        });
                    </script>
                    <?php
                        $td_hidec_ttppyr =  '<td rowspan="2" bgcolor="' . $color . '" align="center" style="border:1px solid #000;" id="CSETPREVYTMTOT_'.$tid.'"></td>';
                         echo '<!--<td bgcolor="' . $color . '" align="center" style="border:1px solid #000;" id="CSETCURMNTTOT_'.$tid.'"></td>-->'
                                . '<td bgcolor="' . $color . '" align="center" style="border:1px solid #000;" id="">'.$total_current_year.'</td>';
//                                . '<td bgcolor="' . $color . '" align="center" style="border:1px solid #000;" id="CSETPREVYTMTOT_'.$tid.'"></td>';
                        echo (($n == 1) ? $td_hidec_ttppyr : '');
                        foreach ($rowdata[$n]["INC"] as $k => $val) {
                            if ($k == 1) {
                                if($n == 1){
                            
                                $total_cm77  += $val["CURRENT_MONTH"];
                                $total_cyy77 +=  $val["CURRENT_YEAR_YTM"];
                                $total_pyy77 +=  $val["PREVIOUS_YEAR_YTM"];
                            
                            }else{

                                $total_cm88  += $val["CURRENT_MONTH"];
                                $total_cyy88 +=  $val["CURRENT_YEAR_YTM"];
                                $total_pyy88 +=  $val["PREVIOUS_YEAR_YTM"];

                            } 
                            $td_hidec_total = '<td rowspan="2" bgcolor="' . $color . '" class="" align="center" style="border:1px solid #000;">' . ($rowdata[$n]["tatalIncDay"]) . '</td>';   
                            $td_hidec_fatal = '<td rowspan="2" bgcolor="' . $color . '" class="" align="center" style="border:1px solid #000;">' . ($rowdata[$n]["FatalDate"]) . '</td>';
                            $td_hidec_npyr  = '<td rowspan="2" bgcolor="' . $color . '" class="CSETPREVYTMDEDUCT_'.$k.'_'.$tid.' CSETPREVYTM_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["PREVIOUS_YEAR_YTM"]) . '</td>';
                            echo '<td bgcolor="' . $color . '" class="CSETCMNTHDEDUCT_'.$k.'_'.$tid.' CSETCMNTH_' . $k . '" align="center" style="border:1px solid #000;" >' . ($val["CURRENT_MONTH"]) . '</td>'
                                . '<td bgcolor="' . $color . '" class="CSETCURYTMDEDUCT_'.$k.'_'.$tid.' CSETCURYTM_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["CURRENT_YEAR_YTM"]) . '</td>';
//                                . '<td bgcolor="' . $color . '" class="CSETPREVYTMDEDUCT_'.$k.'_'.$tid.' CSETPREVYTM_' . $k . '" align="center" style="border:1px solid #000;">' . ($val["PREVIOUS_YEAR_YTM"]) . '</td>';
                            echo (($n == 1) ? $td_hidec_npyr : '');
                            echo   '<td></td>';
                            echo (($n == 1) ? $td_hidec_total : '');
                            echo (($n == 1) ? $td_hidec_fatal : '');
                                
                            }
                        } 
                        
                        echo '</tr>';
                        
                        $total1 += $total_current_year;
                        $total2 += $total_prev_ytm;
                        $total3 += $total_current_ytm;
//                    }
                    } }
                    ?>
                    <tr  style="font-size: 16px;background:#FFFF00;"> 
                        <th style="border-color:#000;" colspan="2" rowspan="2">TOTAL </th>
                        <th style="border-color:#000;"> E</th>

                        <th style="border-color:#000;" id=""><?php echo $total_cm11; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy11; ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo $total_pyy11; ?></th>
                        

                        <th style="border-color:#000;" id=""><?php echo $total_cm22; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy22; ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo $total_pyy22; ?></th>
                        

                        <th style="border-color:#000;" id=""><?php echo $total_cm33; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy33; ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo $total_pyy33; ?></th>
                        

                        <!--<th style="border-color:#000;" id="PSET_TOT_P1"></th>-->
                        <th style="border-color:#000;" id=""><?php echo ($total_cyy11+$total_cyy22+$total_cyy33); ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo ($total_pyy11+$total_pyy22+$total_pyy33); ?></th>
                        
                        
                        <th style="border-color:#000;" id=""><?php echo $total_cm77; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy77; ?></th>
                        <th style="border-color:#000;" id="" rowspan="2"><?php echo $total_pyy77; ?></th>
                        
                        <th style="background: #fff;"></th>
                        <th style="border-color:#000;" id="" rowspan="3" colspan="3"></th>
                        <th style="background: #fff;" id="" rowspan="3"></th>
                        
                    </tr>
                    <tr  style="font-size: 16px;background:#FFFF00;"> 
                        <!--<th style="border-color:#000;" colspan="2">TOTAL </th>-->
                        <th style="border-color:#000;">M </th>

                        <th style="border-color:#000;" id=""><?php echo $total_cm44; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy44; ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        

                        <th style="border-color:#000;" id=""><?php echo $total_cm55; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy55; ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        

                        <th style="border-color:#000;" id=""><?php echo $total_cm66; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy66; ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        

                        <!--<th style="border-color:#000;" id="PSET_TOT_P1"></th>-->
                        <th style="border-color:#000;" id=""><?php echo ($total_cyy44+$total_cyy55+$total_cyy66); ?></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        
                        
                        <th style="border-color:#000;" id=""><?php echo $total_cm88; ?></th>
                        <th style="border-color:#000;" id=""><?php echo $total_cyy88; ?></th>
                        
                        <th style="background: #fff;"></th>
                        <!--<th style="border-color:#000;" id=""></th>-->
                        <!--<th style="border-color:#000;" id=""></th>-->
                        
                    </tr>
                    <tr  style="font-size: 16px;background:#FFFF00;"> 
                        <th style="border-color:#000;" colspan="2">GRAND TOTAL </th>
                        <th style="border-color:#000;">  </th>
                        
                        
                        <th style="border-color:#000;" id="CSETCMNTH_2"></th>
                        
                        <th style="border-color:#000;" id="CSETCURYTM_2"></th>
                        <th style="border-color:#000;" id="CSETPREVYTM_2"></th>

                        <th style="border-color:#000;" id="CSETCMNTH_3"></th>
                        <th style="border-color:#000;" id="CSETCURYTM_3"></th>
                        <th style="border-color:#000;" id="CSETPREVYTM_3"></th>
                        

                        <th style="border-color:#000;" id="CSETCMNTH_4"></th>
                        <th style="border-color:#000;" id="CSETCURYTM_4"></th>
                        <th style="border-color:#000;" id="CSETPREVYTM_4"></th>

<!--                        <th style="border-color:#000;" id="CSET_TOT_P1"></th>-->
                        <th style="border-color:#000;" id="CSET_TOT_CUR"></th>
                        <th style="border-color:#000;" id="CSET_TOT_PREV"></th>
                        
                        
                        <th style="border-color:#000;" id="CSETCMNTH_1"></th>                        
                        <th style="border-color:#000;" id="CSETCURYTM_1"></th>
                        <th style="border-color:#000;" id="CSETPREVYTM_1"></th>
                        
                        <th style="background: #fff;"></th>                        
                        <!--<th style="border-color:#000;" ></th>-->
                        <!--<th style="border-color:#000;" ></th>-->
                    </tr>
                </tbody>
            </table>
            <div style="clear:both;" class="clearfix" ></div>
        <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
         <?php } ?>
        
    </div>
    </div>
</div>



<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
<link rel="stylesheet" href="<?php echo url("assets/css/jqueryui/jquery-ui.css") ?>">
<script src="<?php echo url("assets/js/jqueryui/jquery.mtz.monthpicker.js") ?>"></script>
<script type="text/javascript">
    $('.month_year_picker').monthpicker({pattern: 'mm-yyyy',
        startYear: 2015,
        finalYear: 2025, });
    var options = {
        startYear: 2010,
        finalYear: 2018,
        openOnFocus: false
    };


    $(document).ready(function () {
        $("#ExportExcel").click(function (e) {
            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html());
            window.open(path);
            e.preventDefault();
        });



    });
    function get_side_total_value(type,tid) {
         var val1= 0;
         var val2 = 0;
         var val3 = 0;
         var val4 = 0;
         var val5 = 0;
         var val6 = 0;
        <?php
            if(!empty($incident_category)){
            foreach ($incident_category as $key => $value) {  
                if($value->id != 1) { ?>
                     val1 +=  parseInt($("."+type+"CMNTHDEDUCT_<?php echo $value->id; ?>_"+tid).text());
                     val2 +=  parseInt($("."+type+"PREVYTMDEDUCT_<?php echo $value->id; ?>_"+tid).text());
                     val3 +=  parseInt($("."+type+"CURYTMDEDUCT_<?php echo $value->id; ?>_"+tid).text());
                     
                     val4 += parseInt($("#"+type+"CMNTH_<?php echo $value->id; ?>").text());
                     val5 += parseInt($("#"+type+"PREVYTM_<?php echo $value->id; ?>").text());
                     val6 += parseInt($("#"+type+"CURYTM_<?php echo $value->id; ?>").text());
            <?php  } } } ?>
              
                $("#"+type+"CURMNTTOT_"+tid).text(val1);
                $("#"+type+"PREVYTMTOT_"+tid).text(val2);
                $("#"+type+"CURYTMTOT_"+tid).text(val3);
                $("#"+type+"_TOT_P1").text(val4);
                $("#"+type+"_TOT_PREV").text(val5);
                $("#"+type+"_TOT_CUR").text(val6);
                
               
    }
    function get_total_value(type,incid) {
            var current_month = 0;
            var prev_ytm = 0;
            var current_ytm = 0;
            $('.'+type+'CMNTH_' +incid).each(function () {
                var c1val = $(this).text()
                current_month += parseInt(c1val);
            });
            $('.'+type+'PREVYTM_' +incid).each(function () {
                var c1val = $(this).text()
                prev_ytm += parseInt(c1val);
            });
            $('.'+type+'CURYTM_' +incid).each(function () {
                var c1val = $(this).text()
                current_ytm += parseInt(c1val);
            });


            $("#"+type+"CMNTH_" +incid).text(current_month);
            $("#"+type+"PREVYTM_" +incid).text(prev_ytm);
            $("#"+type+"CURYTM_" +incid).text(current_ytm);
    }
    
    function get_others_except_syscontrol(type,incid) {
         var val1 =  $("."+type+"CMNTHDEDUCT_"+incid+"_168").text();
      var val2 =  $("."+type+"CMNTHDEDUCT_"+incid+"_4").text();
      var val3 =  $("."+type+"PREVYTMDEDUCT_"+incid+"_168").text();
      var val4 =  $("."+type+"PREVYTMDEDUCT_"+incid+"_4").text();
      var val5 =  $("."+type+"CURYTMDEDUCT_"+incid+"_168").text();
      var val6 =  $("."+type+"CURYTMDEDUCT_"+incid+"_4").text();
    
      var totalvalue1 = parseInt(val2)-parseInt(val1 ? val1 : 0);
      var totalvalue2 = parseInt(val4)-parseInt(val3 ? val3 : 0);
      var totalvalue3 = parseInt(val6)-parseInt(val5 ? val5 : 0);
   
      $("."+type+"CMNTHDEDUCT_"+incid+"_4").text(totalvalue1);
      $("."+type+"PREVYTMDEDUCT_"+incid+"_4").text(totalvalue2);
      $("."+type+"CURYTMDEDUCT_"+incid+"_4").text(totalvalue3);
//      $(".tree_168").remove();
      renumberRows();
    }
function renumberRows() {
				$('#TextBoxesGroup tbody  tr').each(function(index, el){
					$(this).children('td').first().text(function(i,t){
						 index++;
                                                 return (index)
					});
				});
				}
</script>
<script type="text/javascript">  
    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {padding-top:8px;background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        mywindow.print();
        mywindow.close();
        return true;
    }
 </script>
<?php $controller->get_footer(); ?>

</body>
</html>
