<?php
/* MIS Report No. : 05
 * Name         :   OTHER DEPT SCORE CARD TYPE-1
 * Controller   :   otherDeptScoreCardType1Report()
 * Dao          :   get_division_department
 * Created By Sumit
 * Modified By Anima And Pallab
 */

if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller   = load_controller("MisController");
$controller->doAction();
$beanUi       = $controller->beanUi;
$allDistrict  = $beanUi->get_view_data("allDistrict");
$getAuditData = $beanUi->get_view_data("getAuditData");
$getDate      = $beanUi->get_view_data("getDate");
$controller->get_header();
$site_root_url = dirname(url());

?>
<style>
    .none {display: none;}
</style>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">5. OTHER DEPT SCORE CARD TYPE-1</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr />

    <form action="" method="post" id="fyr" enctype="multipart/form-data">
        
        <label class="col-sm-2">Select Month</label>
        <div class="col-sm-4">
            <input required="true" type="text" name="month_year_from" id="month_year_from" class="month_year_picker form-control" value = "<?php echo @$getDate['month_year']; ?>" style="width: 100%;" />
            <span id="financial_year_error"></span>
           
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="submitDate" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="otherDeptScoreCardType1Report.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> <?php //echo "<pre>";print_r($alldatagen);  ?>

    <form action="" method="post" enctype="multipart/form-data">
        
        <?php  if(!empty($getAuditData)){ ?>
        <div>
            <table align="center">
                <tr>
                    <td>
                        <u>
                            
                        </u>
                    </td>
                </tr>
                <tr>
                    <td>
                        <u>
                            
                        </u>
                    </td>
                </tr>
                <tr>
                    <td>
                        <u>
                            
                        </u>
                    </td>
                </tr>
            </table>
        </div>
        <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
        <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">
            <div class="none">
                        <u>
                            NON-MAINS DEPARTMENTS SAFETY PERFORMANCE CARD MONTH : 
                            <?php
                            $date = strtotime('01-'.$getDate['month_year']);
                            echo date('F-Y',$date); 
                            ?>
                        </u>
                <br>
                <br>
                <br>
            </div>
            
        <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;" >
                <thead class="bg-primary">
                    <tr>
                        <th colspan="14">
                            <h4><b>NON-MAINS DEPARTMENTS<br>SAFETY PERFORMANCE CARD<br>
                            <?php
                            $date = strtotime('01-'.$getDate['month_year']); 
                            echo date('F, Y',$date); 
                            ?></b>
                        </h4>
                        </th>
                    </tr>
                    <tr >
                        <th rowspan="3">SL.</th>
                        <th rowspan="3">DEPT.</th>
                        <th  colspan="6"> <?php  echo date('M Y',$date); ?></th>
                        <th  colspan="6">YTM <?php 
                        $PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["curYear"]));
                        echo "'".@$PY[1]."-".$PY[5]; 
                        ?></th>
                    </tr>
                    <tr>                      
                        <th colspan="2">P SET</th>
                        <th colspan="2">C SET</th>
                        <th colspan="2">OVERALL</th>                        
                        <th colspan="2">P SET</th>
                        <th colspan="2">C SET</th>
                        <th colspan="2">YTM <br> OVERALL</th>   
                    </tr>
                    
                    <tr>
                        <th>No of Audit</th>
                        <th>Score</th>
                        <th>No of Audit</th>
                        <th>Score</th>
                        <th>No of Audit</th>
                        <th>Score</th>
                        <th>No of Audit</th>
                        <th>Score</th>
                        <th>No of Audit</th>
                        <th>Score</th>
                        <th>No of Audit</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                     <?php
                        $Sl = 1;
                        $CMPSETSCORETOT = $PY_CSET_OTHERS_TOT = $PY_NOA_OVERALL_TOT = $PY_OTHERS_NOA_TOT = $PY_PCSET_SCORE_AVG = $PY_PCSET_NOA_TOT = $PY_CSET_NOA_TOT = $PY_PSET_NOA_TOT = $CM_SCORE_OVERALL = $CM_NOA_OVERALL_TOT = $CM_OTHERS_NOA_TOT = $CM_PCSET_SCORE_AVG = $CM_OTHERS_NOA_TOT = $CM_PCSET_NOA_TOT = $CM_PSET_NOA_TOT = $CMCSETSCORETOT = $CMPCSETSCORETOT = $CMOTHERSSCORETOT = $PYPSETSCORETOT = $PYCSETSCORETOT = $PYPCSETSCORETOT = $PYOTHERSSCORETOT = $PY_OTHERS_SCORE_AVG = $CM_OTHERS_SCORE_AVG = 0;
                        for($i=0; $i<count(@$getAuditData['allDistrict']); $i++){   
                        ?>
                    <tr>
                        <td style="border:1px solid #000 !important;"><?php echo $Sl; ?></td>
                        <td style="border:1px solid #000 !important;"><?php echo @$getAuditData['allDistrict'][$i][0]->name; ?></td>
                        <td bgcolor="#CCFFFF" align="center" style="border:1px solid #000 !important;"><?php echo @$CM_PSET_NOA    = round(@$getAuditData['P_SET_CURRENT'][$i]['totaldiv'],2); $CM_PSET_NOA_TOT += $CM_PSET_NOA;  ?></td>
                        <td bgcolor="#c5ff93" align="center" style="border:1px solid #000 !important;"><?php echo @$CM_PSET_SCORE  = round(@$getAuditData['P_SET_CURRENT'][$i]['score'],2); ?></td>
                        <td bgcolor="#CCFFFF" align="center" style="border:1px solid #000 !important;"><?php echo @$CM_CSET_NOA    = round(@$getAuditData['C_SET_CURRENT'][$i]['totaldiv'],2); @$CM_CSET_NOA_TOT += $CM_CSET_NOA; ?></td>
                        <td bgcolor="#c5ff93" align="center" style="border:1px solid #000 !important;"><?php echo @$CM_CSET_SCORE  = round(@$getAuditData['C_SET_CURRENT'][$i]['score'],2); ?></td>
                        <?php
                         @$CMPSETSCORETOT += (@$CM_PSET_NOA * @$CM_PSET_SCORE);
                         if(@$CMPSETSCORETOT != 0) @$CM_PSET_SCORE_AVG = round((@$CMPSETSCORETOT / @$CM_PSET_NOA_TOT), 2);
                         @$CMCSETSCORETOT += (@$CM_CSET_NOA * @$CM_CSET_SCORE);
                         if(@$CMCSETSCORETOT != 0) @$CM_CSET_SCORE_AVG = round((@$CMCSETSCORETOT / @$CM_CSET_NOA_TOT), 2 );
                         @$CMPCSETSCORETOT += (@$CM_PCSET_NOA * @$CM_PCSET_SCORE);
                        ?>
                        <td bgcolor="#CCFFFF" align="center" style="border:1px solid #000 !important;"><?php echo @$CM_NOA_OVERALL = round((@$CM_PSET_NOA + @$CM_CSET_NOA + @$CM_PCSET_NOA + @$CM_OTHERS_NOA),2); @$CM_NOA_OVERALL_TOT += @$CM_NOA_OVERALL;  ?></td>
                        <td bgcolor="#c5ff93" align="center" style="border:1px solid #000 !important;"><?php
                        @$DXY = (@$CM_PSET_NOA + @$CM_CSET_NOA + @$CM_PCSET_NOA + @$CM_OTHERS_NOA);
                        if(@$DXY != 0 || @$DXY != ''){
                         echo @$CM_SCORE_OVERALL = round(((@$CM_PSET_NOA * @$CM_PSET_SCORE + @$CM_CSET_NOA * @$CM_CSET_SCORE + @$CM_PCSET_NOA * @$CM_PCSET_SCORE + @$CM_OTHERS_NOA * @$CM_OTHERS_SCORE) / @$DXY ), 2);
                        }else{ echo $CM_SCORE_OVERALL = 0; }  ?></td>
                        
                        <td bgcolor="#CCFFFF" align="center" style="border:1px solid #000 !important;"><?php echo $PY_PSET_NOA     = round(@$getAuditData['P_SET_YTM'][$i]['totaldiv'],2); $PY_PSET_NOA_TOT += $PY_PSET_NOA; ?></td>
                        <td bgcolor="#c5ff93" align="center" style="border:1px solid #000 !important;"><?php echo $PY_PSET_SCORE   = round(@$getAuditData['P_SET_YTM'][$i]['score'],2); ?></td>
                        <td bgcolor="#CCFFFF" align="center" style="border:1px solid #000 !important;"><?php echo $PY_CSET_NOA     = round(@$getAuditData['C_SET_YTM'][$i]['totaldiv'],2); $PY_CSET_NOA_TOT += $PY_CSET_NOA; ?></td>
                        <td bgcolor="#c5ff93" align="center" style="border:1px solid #000 !important;"><?php echo $PY_CSET_SCORE   = round(@$getAuditData['C_SET_YTM'][$i]['score'],2); ?></td>
                        <?php
                         @$PYPSETSCORETOT += (@$PY_PSET_NOA * @$PY_PSET_SCORE);
                         if(@$PYPSETSCORETOT != 0) @$PY_PSET_SCORE_AVG = round((@$PYPSETSCORETOT / @$PY_PSET_NOA_TOT), 2);
                         @$PYCSETSCORETOT += (@$PY_CSET_NOA * @$PY_CSET_SCORE);
                         if(@$PYCSETSCORETOT != 0)@$PY_CSET_SCORE_AVG = round((@$PYCSETSCORETOT / @$PY_CSET_NOA_TOT), 2 );
                         @$PYPCSETSCORETOT += (@$PY_PCSET_NOA * @$PY_PCSET_SCORE);
                        ?>
                        <td bgcolor="#CCFFFF" align="center" style="border:1px solid #000 !important;"><?php echo @$PY_NOA_OVERALL = round(($PY_PSET_NOA + $PY_CSET_NOA + $PY_PCSET_NOA + $PY_OTHERS_NOA),2); $PY_NOA_OVERALL_TOT += $PY_NOA_OVERALL;  ?></td>
                        <td bgcolor="#c5ff93" align="center" style="border:1px solid #000 !important;"><?php echo @$PY_SCORE_OVERALL = round((($PY_PSET_NOA * $PY_PSET_SCORE + $PY_CSET_NOA * $PY_CSET_SCORE + $PY_PCSET_NOA * $PY_PCSET_SCORE + $PY_OTHERS_NOA * $PY_OTHERS_SCORE) / ($PY_PSET_NOA + $PY_CSET_NOA + $PY_PCSET_NOA + $PY_OTHERS_NOA)), 2); ?></td>   
                    </tr>
                    <?php $Sl++; } ?>
                    <tr> 
                        <th bgcolor="#FCD5B5" colspan="2" style="border:1px solid #000 !important;">TOTAL / AVG</th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$CM_PSET_NOA_TOT; ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$CM_PSET_SCORE_AVG;  ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$CM_CSET_NOA_TOT; ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$CM_CSET_SCORE_AVG; ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$CM_NOA_OVERALL_TOT;  ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$CM_SCORE_OVERALL_AVG = round((($CM_PSET_NOA_TOT * $CM_PSET_SCORE_AVG + $CM_CSET_NOA_TOT * $CM_CSET_SCORE_AVG + $CM_PCSET_NOA_TOT * $CM_PCSET_SCORE_AVG + $CM_OTHERS_NOA_TOT * $CM_OTHERS_SCORE_AVG) / ($CM_PSET_NOA_TOT + $CM_CSET_NOA_TOT + $CM_PCSET_NOA_TOT + $CM_OTHERS_NOA_TOT)), 2); ?></th>
                         
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$PY_PSET_NOA_TOT; ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$PY_PSET_SCORE_AVG;  ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$PY_CSET_NOA_TOT; ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$PY_CSET_SCORE_AVG; ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$PY_NOA_OVERALL_TOT;  ?></th>
                        <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$PY_SCORE_OVERALL_AVG = round((($PY_PSET_NOA_TOT * $PY_PSET_SCORE_AVG + $PY_CSET_NOA_TOT * $PY_CSET_SCORE_AVG + $PY_PCSET_NOA_TOT * $PY_PCSET_SCORE_AVG + $PY_OTHERS_NOA_TOT * $PY_OTHERS_SCORE_AVG) / ($PY_PSET_NOA_TOT + $PY_CSET_NOA_TOT + $PY_PCSET_NOA_TOT + $PY_CSET_OTHERS_TOT)), 2); ?></th>                        
                    </tr>
                </tbody>
              

            </table>
            <div class="print1" style="float: left; border:1px solid #000; padding:3px 8px;">
                        NOTE : P-Sets including Call Centres</td>
                    </div>
            <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
        </div>
</div>
<?php  } ?>
    </form>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>
<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
<link rel="stylesheet" href="<?php echo url("assets/css/jqueryui/jquery-ui.css") ?>">
<script src="<?php echo url("assets/js/jqueryui/jquery.mtz.monthpicker.js") ?>"></script>
<script>
    $('.month_year_picker').monthpicker({pattern: 'mm-yyyy',
        startYear: 2015,
        finalYear: 2025, });
    var options = {
        startYear: 2010,
        finalYear: 2018,
        openOnFocus: false
    };
</script>
<script type='text/javascript'>
$(document).ready(function(){
    $("#ExportExcel").click(function(e){
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
    mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
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
