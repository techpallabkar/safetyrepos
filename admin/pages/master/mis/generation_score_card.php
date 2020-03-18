<?php
/* MIS Report No. : 07
 * Name         :   Generation Score Card
 * Controller   :   generation_score_card()
 * Dao          :   getAuditCount
 * Created By anima
 * Modified By Pallab   
 */

if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$month_year               =   $beanUi->get_view_data("month_year");
$month_of_year_back       =   $beanUi->get_view_data("month_of_year_back");
$month_of_year_back2      =   $beanUi->get_view_data("month_of_year_back2");
$fetchAllData             =   $beanUi->get_view_data("fetchAllData");
$getDate                  =   $beanUi->get_view_data("getDate");
$incident_cm              =   $beanUi->get_view_data("incident_cm");
$incident_ytm             =   $beanUi->get_view_data("incident_ytm");
$incident_prev            =   $beanUi->get_view_data("incident_prev");
$incident_category_id     =   $beanUi->get_view_data("incident_category_id");
$fetchAllData             =   $beanUi->get_view_data("fetchAllData");

$cfy_date = explode(",", @$getDate['CFY']);
@$current_year = date("Y", strtotime($cfy_date[0]))." - ".date("y", strtotime($cfy_date[1]));
$pfy_date = explode(",", @$getDate['PFY']);
@$prev_year = date("Y", strtotime($pfy_date[0]))." - ".date("y", strtotime($pfy_date[1]));

$controller->get_header();
$site_root_url = dirname(url());

//show($fetchAllData);
?>
<style>
 #parent {
        height: 570px;
    }
</style>
<script src="<?php echo url("assets/js/tableHeadFixer.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
        $("#fixTable").tableHeadFixer({'foot': true, 'head': true});
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">7. Generation Score Card</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>
    <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php echo ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
        <div class="holder">
            <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
            <input type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo @$month_year; ?>" style="width:250px;"  required/>
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="generation_score_card.php" class="btn btn-danger btn-sm">Reset</a>                 
        </div>
        <hr class="no-margin"/>
    </form>
    <!--REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES--> 
    <?php if (!empty($fetchAllData)) { ?>
    <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
        Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
    <div id='TBL_EXPORT'> 
        <div class="print-friendly" id="divToPrint">
       
            <div id="parent">    
            <table id="fixTable" class="table table-bordered table-condensed scrollTable totaldata" style="font-size:13px;font-weight:bold;" >
                <thead class="bg-primary fixedHeader">
                    <tr>
                        <th colspan="27">
                            Generation Division <br>
                            SAFETY PERFORMANCE CARD<br>
                            MONTH  : <?php echo date("F,Y", strtotime($getDate['CM']."-01")); ?>
                        </th>
                    </tr>
                    <tr>
                        <th width="6%" rowspan="3" style="vertical-align: middle;">Sl.<br>No</th>
                        <th width="20%" rowspan="3" style="vertical-align: middle;">Station</th>
                        <th colspan="8">FY' <?php echo $prev_year;?> </th>
                        <th colspan="8"><?php echo date("M Y", strtotime($getDate['CM']."-01")); ?></th>
                        <th colspan="9">FY' <?php echo $current_year; ?> </th>
                    </tr>
                    <tr>
                        <th colspan="3">P-SET</th>
                        <th colspan="3">C-SET</th>
                        <th colspan="2">OVERALL</th>
                        <th colspan="3">P-SET</th>
                        <th colspan="3">C-SET</th>
                        <th colspan="2">OVERALL</th>
                        <th colspan="3">P-SET (YTM)</th>
                        <th colspan="3">C-SET (YTM)</th>
                        <th colspan="2">OVERALL (YTM)</th>
                        <th rowspan="2" style="vertical-align: middle;"> IMPROVEMENT(%) </th>
                    </tr>
                    <tr>
                        <th>Nos of Audits</th>
                        <th>Dept</th>
                        <th>Score</th>
                        
                        <th>Nos of Audits</th>
                        <th>Dept</th>
                        <th>Score</th>

                        <th>Nos of Audits</th>
                        <th>Score</th>
                         <th>Nos of Audits</th>
                        <th>Dept</th>
                        <th>Score</th>
                        
                        <th>Nos of Audits</th>
                        <th>Dept</th>
                        <th>Score</th>
                        
                        <th>Nos of Audits</th>
                        <th>Score</th>
                         <th>Nos of Audits</th>
                        <th>Dept</th>
                        <th>Score</th>
                        
                        <th>Nos of Audits</th>
                        <th>Dept</th>
                        <th>Score</th>
                        
                        <th>Nos of Audits</th>
                        <th>Score</th>   
                    </tr>
                </thead>
                <tbody class="scrollContent">
                    <?php
                  if(!empty($fetchAllData)){
                    $i = 0;
                     $score1 = $score2 = $score3 = $improvement = 0;
                        foreach ($fetchAllData as $key => $value) {
                            $rowspan = count($value);
                            
                            foreach( $value as $key1 => $value1 ) {
                                $i = $i+1;
                                if( $i == 1 || $i == 10) {
                                    $rowspn = $rowspan;
                                } else {
                                    $rowspn = 0;
                                }
                            echo '<tr><td style="border:1px solid #000 !important;" align="center" bgcolor="#FFFF00">'.($i).'.</td>';
                            if($rowspn != 0) {
                            echo '<td align="center" valign="middle" style="border:1px solid #000 !important;vertical-align:middle;" bgcolor="#CCFFFF" rowspan="'.$rowspn.'" >'.($key).'</td>';
                            }
                           
                            $totalcount1 = (@$value1["PREV_YEAR"]["PSET"]["TOTAL_COUNT"] + 
                                    @$value1["PREV_YEAR"]["CSET"]["TOTAL_COUNT"]);
                            if($totalcount1 != 0){
                                $score1 = round(((((@$value1["PREV_YEAR"]["PSET"]["TOTAL_COUNT"])*($value1["PREV_YEAR"]["PSET"]["TOTAL_AVG"]))+
                                    (($value1["PREV_YEAR"]["CSET"]["TOTAL_COUNT"])*($value1["PREV_YEAR"]["CSET"]["TOTAL_AVG"])) ) / $totalcount1),2);
                            }
                            $totalcount2 = ($value1["CURRENT_MONTH"]["PSET"]["TOTAL_COUNT"] + $value1["CURRENT_MONTH"]["CSET"]["TOTAL_COUNT"]);
                             if($totalcount2 != 0){
                                $score2 = round((((($value1["CURRENT_MONTH"]["PSET"]["TOTAL_COUNT"])*($value1["CURRENT_MONTH"]["PSET"]["TOTAL_AVG"]))+
                                    ((@$value1["CURRENT_MONTH"]["CSET"]["TOTAL_COUNT"])*($value1["CURRENT_MONTH"]["CSET"]["TOTAL_AVG"])) ) / $totalcount2),2);
                             }
                            $totalcount3 = (@$value1["CURRENT_YTM"]["PSET"]["TOTAL_COUNT"]+ @$value1["CURRENT_YTM"]["CSET"]["TOTAL_COUNT"]);
                            if($totalcount3 != 0){
                             $score3 = round((((($value1["CURRENT_YTM"]["PSET"]["TOTAL_COUNT"])*($value1["CURRENT_YTM"]["PSET"]["TOTAL_AVG"]))+
                                    (($value1["CURRENT_YTM"]["CSET"]["TOTAL_COUNT"])*($value1["CURRENT_YTM"]["CSET"]["TOTAL_AVG"])) ) / $totalcount3),2);   
                            }
                            //if($score1 !=0 ){
                             $improvement = (($score1 == 0) ? 0 :round(((($score3-$score1)/$score1)*100),2));
                           // }
                            echo '<td style="border:1px solid #000 !important;" align="center" >'.(@$value1["PREV_YEAR"]["PSET"]["TOTAL_COUNT"]).'</td>
                                <td style="border:1px solid #000 !important;" bgcolor="#e3ff96" align="center" >'.($value1["DISTRICT"]).'</td>
                                <td style="border:1px solid #000 !important;" align="center" >'.(@$value1["PREV_YEAR"]["PSET"]["TOTAL_AVG"]).'</td>
                               
                                <td style="border:1px solid #000 !important;" align="center" >'.($value1["PREV_YEAR"]["CSET"]["TOTAL_COUNT"]).'</td>
                                <td style="border:1px solid #000 !important;" bgcolor="#e3ff96" align="center" >'.($value1["DISTRICT"]).'</td>
                                <td style="border:1px solid #000 !important;" align="center">'.($value1["PREV_YEAR"]["CSET"]["TOTAL_AVG"]).'</td>                              
                                
                                <td style="border:1px solid #000 !important;" align="center" bgcolor="#B9CDE5">'.$totalcount1.'</td>
                                <td style="border:1px solid #000 !important;" align="center" bgcolor="#B9CDE5">'.$score1.'</td>
                                    
                                <td style="border:1px solid #000 !important;" align="center">'.($value1["CURRENT_MONTH"]["PSET"]["TOTAL_COUNT"]).'</td>
                                <td style="border:1px solid #000 !important;" bgcolor="#e3ff96" align="center">'.($value1["DISTRICT"]).'</td>
                                <td style="border:1px solid #000 !important;" align="center" >'.($value1["CURRENT_MONTH"]["PSET"]["TOTAL_AVG"]).'</td>
                                    
                                <td style="border:1px solid #000 !important;" align="center">'.($value1["CURRENT_MONTH"]["CSET"]["TOTAL_COUNT"]).'</td>
                                <td style="border:1px solid #000 !important;" bgcolor="#e3ff96" align="center">'.($value1["DISTRICT"]).'</td>
                                <td style="border:1px solid #000 !important;" align="center">'.($value1["CURRENT_MONTH"]["CSET"]["TOTAL_AVG"]).'</td>
                                    
                                <td style="border:1px solid #000 !important;" align="center" bgcolor="#FCD5B5">'.$totalcount2.'</td>
                                <td style="border:1px solid #000 !important;" align="center" bgcolor="#FCD5B5">'.$score2.'</td>
                                    
                                <td style="border:1px solid #000 !important;" align="center">'.($value1["CURRENT_YTM"]["PSET"]["TOTAL_COUNT"]).'</td>
                                <td style="border:1px solid #000 !important;" bgcolor="#e3ff96" align="center">'.($value1["DISTRICT"]).'</td>
                                <td style="border:1px solid #000 !important;" align="center">'.($value1["CURRENT_YTM"]["PSET"]["TOTAL_AVG"]).'</td>
                                    
                                <td style="border:1px solid #000 !important;" align="center">'.($value1["CURRENT_YTM"]["CSET"]["TOTAL_COUNT"]).'</td>
                                <td style="border:1px solid #000 !important;" bgcolor="#e3ff96" align="center">'.($value1["DISTRICT"]).'</td>
                                <td style="border:1px solid #000 !important;" align="center" >'.($value1["CURRENT_YTM"]["CSET"]["TOTAL_AVG"]).'</td>
                                    
                                <td style="border:1px solid #000 !important;" align="center" bgcolor="#DDD9C3">'.$totalcount3.'</td>
                                <td style="border:1px solid #000 !important;" align="center" bgcolor="#DDD9C3">'.$score3.'</td>
                                    
                                <td style="border:1px solid #000 !important;" align="center" bgcolor="#DDD9C3">'.$improvement.'%</td>
                            </tr>';
                            $score1 = $score2 = $score3 = $improvement = 0;
                            }
                         
                        }
                      }
                    ?>
                    
                </tbody>
            </table>
            </div>
         </div>    
            <div class="print1" style="float: left; border:1px solid #000; padding:3px 8px;">
                        NOTE : P-Sets including Call Centres</td>
                    </div>
            <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
        
    
  
     <?php } ?>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>


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
    $(document).ready(function () {

<?php
if(!empty($incident_category)){
foreach ($incident_category as $key => $value) { ?>
        
        get_total_value('PSET',<?php echo $value->id; ?>);
        get_total_value('CSET',<?php echo $value->id; ?>);
      //  get_total_value('PCSET',<?php echo $value->id; ?>);
      //  get_total_value('OTHERS',<?php echo $value->id; ?>);
<?php } } ?>


    });
</script>
<script type="text/javascript">  
    function PrintDiv()
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);margin:.7in .3in;}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:8px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;padding:2px 1px;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;word-break:break-all;width:2.05%;}body{margin:0;padding:0;}</style></head><body>');
    //mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}
 </script>
