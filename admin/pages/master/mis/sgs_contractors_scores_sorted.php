<?php
/* MIS Report No. : 14
 * Name         :   SGS Contractors Scores -Sorted 
 * Controller   :   sgs_contractors_scores_sorted()
 * Dao          :   get_contractor_list_Gen_Sgs,getAllContractorbyCode_Gen_Sgs
 * Created By pallab
 */


if (file_exists("../../lib/var.inc.php"))
  require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$getAllContractorData = $beanUi->get_view_data("get_all_contractor_data");
$getViewDate = $beanUi->get_view_data("get_view_date");
@$previous_year_arr = explode(" AND ",str_replace("'","",$getViewDate["PREV_YEAR"]));
@$previous_year = date('y', strtotime($previous_year_arr[0]))."-".date('y', strtotime($previous_year_arr[1]));
@$current_year_ytm_arr =  explode(" AND ",str_replace("'","",$getViewDate["CUR_YEAR_YTM"]));
@$current_year_ytm = date('M y', strtotime('-3 days', strtotime($current_year_ytm_arr[1]))); 
@$current_month_arr =  explode(" AND ",str_replace("'","",$getViewDate["CURRENT_MONTH"]));
$current_month = date('M y', strtotime($current_month_arr[0])); 

@$current_year_arr = explode(" AND ",str_replace("'","",$getViewDate["CURR_YEAR"]));
@$current_year = date('y', strtotime($current_year_arr[0]))."-".date('y', strtotime($current_year_arr[1]));

$controller->get_header();
$site_root_url = dirname(url());
?>
<style>.pink-bg th{background: #FF8080;font-weight: normal;}.green-bg th{background:#00FF00;font-weight: normal;}.white-bg th{background: #fff;font-weight: normal;}</style>
<div class="container1">
    
     <?php echo $beanUi->get_message(); ?>
    <h1 class="heading">14.SGS Contractors Scores -Sorted
        <?php //echo ( $mode == "view" ) ? '<a style="float: right;" href="mdReportlist.php" class="btn btn-danger btn-xs">Back</a>' : ''; ?>
    </h1> 
    <hr/>
    <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php //echo ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
        <div class="holder">
            <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
            <input type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo @$getViewDate["month_year"]; ?>" style="width:250px;"  required>
            <input type="hidden" name="_action" value="get_report" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="sgs_contractors_scores_sorted.php" class="btn btn-danger btn-sm">Reset</a>                 
        </div>
        <hr class="no-margin"/>
    </form>
    
    
      <?php 
      if(!empty($getAllContractorData)){ ?>
    <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
        Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
    <div id='TBL_EXPORT'>
    <div class="print-friendly" id="divToPrint">
    <div class="wrapper2"> 
        
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr><th colspan="10">  SGS CONTRACTORS SCORES-SORTED</th></tr>
            <tr>
                <th rowspan="2">SL.NO</th>
                <!--<th rowspan="2">IDENTIFICATION CODE</th>-->
                <th rowspan="2">VENDORS</th>
                <th>COMPOSIT SCORE IN FY'<?php echo $previous_year; ?></th>
                <th colspan="2">YTM <?php echo $current_year_ytm; ?></th>
                <th colspan="2">FIGURES FOR THE MONTH OF '<?php echo $current_month; ?></th>
                <th colspan="2">YTM -<?php echo $current_month; ?></th>
            </tr>
            <tr>
                <th>% Avg.</th>
                <th>Score</th>
                <th>No of Audit</th>
                <th>Score</th>
                <th>No of Audit</th>
                <th>Score</th>
                <th>No of Audit</th>
            </tr>
            </thead>
            <tbody align="center">
                <?php
                $i = 1;
                $i1 = 1;
                $flag =0;
                $arr_sort = array();
                /*get last five lowest value*/
                foreach( $getAllContractorData as $key1 => $rowdata1 ) {
                    $sc1 = round($rowdata1["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"],2);
                    if($i1 <= 5){ 
                    }else if($sc1 > 0 ){
                    $arr_sort[] = $sc1;
                    }else {}
                    $i1++;
                }
                $items = array_slice($arr_sort, -5);
                /*get last five lowest value*/
                
                foreach( $getAllContractorData as $key => $rowdata ) { 
                    $sc = round($rowdata["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"],2);
                    $cls ="";
                if($i <= 5){ 
                    echo '<tr class="green-bg">';
                }else if($sc > 0 ){
                    
                    $cls = "lowestValue";
                    if(in_array($sc, $items)) {
                        echo '<tr class="pink-bg">';
                    } else {
                    echo '<tr class="white-bg">';
                    }
                }else {echo '<tr class="white-bg">';}
                if($sc != 0){
                echo '<th style="border:1px solid #000;">'.($i++).'.</th>
                    <th style="border:1px solid #000;"  align="left" >M/s '.($rowdata["VENDOR_NAME"]).'</th>
                    <th style="border:1px solid #000;" >'.round($rowdata["PREV_YEAR"]["SCORE"],2).'</th>
                        
                    <th style="border:1px solid #000;" >'.round($rowdata["CUR_YEAR_PREV_MONTH_YTM"]["SCORE"],2).'</th>
                    <th style="border:1px solid #000;" >'.($rowdata["CUR_YEAR_PREV_MONTH_YTM"]["NO_OF_AUDIT"]).'</th>
                        
                    <th style="border:1px solid #000;" >'.round($rowdata["CURRENT_MONTH"]["SCORE"],2).'</th>
                    <th style="border:1px solid #000;" >'.($rowdata["CURRENT_MONTH"]["NO_OF_AUDIT"]).'</th>
                        
                    <th style="border:1px solid #000;" class="'.$cls.'">'.round($rowdata["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"],2).'</th>
                    <th style="border:1px solid #000;" >'.($rowdata["CURR_YEAR_CURR_MNTH_YTM"]["NO_OF_AUDIT"]).'</th>
                </tr>';
                }
                }
                 ?>
            </tbody>
            
        </table>
    </div> 
        <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
    </div>
    </div>
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
  
</script>
<script type="text/javascript">  
    function PrintDiv()
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #000000 !important;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}.green-bg th{background:#00FF00 !important;font-weight:normal;}.pink-bg th{background-color:#ff8080 !important;font-weight:normal;}.white-bg th{background:#ffffff !important;font-weight:normal;}</style></head><body>');
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