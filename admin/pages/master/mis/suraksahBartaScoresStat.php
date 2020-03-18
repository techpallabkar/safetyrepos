<?php
/* MIS Report No. : 09
 * Name         :   Suraksah Barta Scores Stat
 * Controller   :   suraksahBartaScoresStat()
 * Dao          :   division_child_unit_count,getPPEAuditData,getSITEAuditData,getSiteAuditCount
 * Created By Anima Mahato
 */
if (file_exists("../../lib/var.inc.php"))
require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$getPPEAuditData = $beanUi->get_view_data("getPPEAuditData");
$getSITEAuditData = $beanUi->get_view_data("getSITEAuditData");
$getDate = $beanUi->get_view_data("getDate");
$controller->get_header();
$site_root_url = dirname(url());
?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">9. Generation & Distribution Gear & Site Audit Report
</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
      <form action="" method="post" id="fyr" enctype="multipart/form-data">
        
        <label class="col-sm-2">Select Month</label>
        <div class="col-sm-4">
            <input required="true" type="text" name="month_year_from" id="month_year_from" value="<?php echo @$_REQUEST["month_year_from"]; ?>" class="month_year_picker form-control" value = "<?php echo @$getDate['month_year']; ?>" style="width: 100%;" />
            <span id="financial_year_error"></span>
           
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="submitData" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="surakshaBartaIncIdentStatics.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr/>
        <?php if(!empty($_POST)) { ?>
 <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">  
        <div>
        <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;" >
                <thead class="bg-primary">
                    <tr>
                        <th  colspan="13">PPE AND GEAR AUDIT PERFORMANCE IN <?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?> & <?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["CURR_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                    </tr>
                    <tr class="bg-primary">
                        <th rowspan="4">SL</th>
                        <th rowspan="4">DEPT / DIST</th>
                    </tr>
                    <tr>
                        <th colspan="5">P-SET</th>
                        <th></th>
                        <th colspan="5">C-SET</th>
                    </tr>
                    <tr>
                        <th rowspan="2">NO. OF SET / UNIT</th>
                        <th colspan="2">FY:<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                        <th colspan="2">YTM:<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["CURR_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                        <th></th>
                        <th rowspan="2">NO. OF SET / UNIT</th>
                        <th colspan="2">FY:<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                        <th colspan="2">YTM:<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["CURR_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                    </tr>
                    <tr>
                        <th> AUDIT COUNT </th>
                        <th> % AVG </th>
                        <th> AUDIT COUNT </th>
                        <th> % AVG </th>
                        <th></th>
                        <th> AUDIT COUNT </th>
                        <th> % AVG </th>
                        <th> AUDIT COUNT </th>
                        <th> % AVG </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $Sl = 1;
                        @$NOD = count(@$getPPEAuditData['allDistrict']);
                        for($i=0; $i<$NOD; $i++){
                            //if(@$getPPEAuditData['allDistrict'][$i][0]->id != 182 ){
                        ?>
                        <td style="border:1px solid #000 !important;"><?php echo $Sl; ?></td>
                        <td bgcolor="#B9CDE5" style="border:1px solid #000 !important;"><?php echo @$getPPEAuditData['allDistrict'][$i][0]->name; ?></td>
                        <?php   
                       
                        if(@$getPPEAuditData['allDistrict'][$i][0]->id == 10 ){
                        ?>
                        <!--P-SET-->
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffdd99"><?php echo $P_SET_UNIT = 'NA'; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffb3ff"><?php echo $P_C1 = 'NA'; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffff99"><?php echo $P_D1 = 'NA'; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#b3ffff"><?php echo $P_E1 = 'NA'; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffaa80"><?php echo $P_F1 = 'NA'; ?></td>
                        <?php }else{ ?>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffdd99"><?php echo $P_SET_UNIT = @$getPPEAuditData['P_SET_UNIT'][$i]; @$TOT_PSET_UNIT += $P_SET_UNIT; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffb3ff"><?php echo $P_C1 = round(@$getPPEAuditData['P_SET_PREV'][$i]['totaldiv'],2);  @$PPE_P_C1_TOT = @$PPE_P_C1_TOT + $P_C1; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffff99"><?php echo $P_D1 = round(@$getPPEAuditData['P_SET_PREV'][$i]['score'],2); ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#b3ffff"><?php echo $P_E1 = round(@$getPPEAuditData['P_SET_CURRENT'][$i]['totaldiv'],2);  @$PPE_P_E1_TOT = @$PPE_P_E1_TOT + $P_E1; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffaa80"><?php echo $P_F1 = round(@$getPPEAuditData['P_SET_CURRENT'][$i]['score'],2); ?></td>
                        <?php } ?>
                        <td style="border:1px solid #000 !important;"></td>
                        <!--C-SET-->
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffdd99"><?php echo $C_SET_UNIT = @$getPPEAuditData['C_SET_UNIT'][$i]; @$TOT_CSET_UNIT += $C_SET_UNIT; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffb3ff"><?php echo $C_C1 = round(@$getPPEAuditData['C_SET_PREV'][$i]['totaldiv'],2);  @$PPE_C_C1_TOT = @$PPE_C_C1_TOT + $C_C1; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffff99"><?php echo $C_D1 = round(@$getPPEAuditData['C_SET_PREV'][$i]['score'],2); ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#b3ffff"><?php echo $C_E1 = round(@$getPPEAuditData['C_SET_CURRENT'][$i]['totaldiv'],2); @$PPE_C_E1_TOT = @$PPE_C_E1_TOT + $C_E1; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffaa80"><?php echo $C_F1 = round(@$getPPEAuditData['C_SET_CURRENT'][$i]['score'],2); ?></td>
                    </tr>
                        <?php $Sl++;  
                            //} 
                        } ?>
                    <tr bgcolor="#F2DCDB" style="border:1px solid #000 !important;"> 
                        <th style="border:1px solid #000 !important;" colspan="2">TOTAL / AVG</th>
                        <th style="border:1px solid #000 !important;"><?php echo $TOT_PSET_UNIT; ?></th>
                        <th style="border:1px solid #000 !important;"><?php echo @$PPE_P_C1_TOT;?></th>
                        <th style="border:1px solid #000 !important;">--</th>
                        <th style="border:1px solid #000 !important;"><?php echo @$PPE_P_E1_TOT; ?></th>
                        <th style="border:1px solid #000 !important;">--</th>
                        <th style="border:1px solid #000 !important;">--</th>
                        <th style="border:1px solid #000 !important;"><?php echo $TOT_CSET_UNIT; ?></th>
                        <th style="border:1px solid #000 !important;"><?php echo @$PPE_C_C1_TOT; ?></th>
                        <th style="border:1px solid #000 !important;">--</th>
                        <th style="border:1px solid #000 !important;"><?php echo @$PPE_C_E1_TOT; ?></th>
                        <th style="border:1px solid #000 !important;">--</th>
                    </tr>
                </tbody>
            </table>
        
    <!--END PPE AUDIT-->
    
    <!--START SITE AUDIT-->     
        <table border="1" class="table table-bordered table-condensed table-responsive table-siteAudit" style="font-size:13px;" >
                <thead class="bg-primary">
                    <tr>
                        <th  colspan="13">SITE SAFETY AUDIT PERFORMANCE IN <?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?> & <?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["CURR_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                    </tr>
                    <tr class="bg-primary">
                        <th rowspan="4">SL</th>
                        <th rowspan="4">DEPT / DIST</th>
                    </tr>
                    <tr>
                        <th colspan="5">P-SET</th>
                        <th></th>
                        <th colspan="5">C-SET</th>
                    </tr>                    
                    <tr>
                        <th rowspan="2">NO. OF SET / UNIT</th>
                        <th colspan="2">FY:<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                        <th colspan="2">YTM:<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["CURR_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                        <th></th>
                        <th rowspan="2">NO. OF SET / UNIT</th>
                        <th colspan="2">FY:<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                        <th colspan="2">YTM:<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["CURR_Y"]));
                        echo @$PY[1]."-".@$PY[5];
                        ?></th>
                    </tr>
                    <tr>
                        <th> AUDIT COUNT </th>
                        <th> % AVG </th>
                        <th> AUDIT COUNT </th>
                        <th> % AVG </th>
                        <th></th>
                        <th> AUDIT COUNT </th>
                        <th> % AVG </th>
                        <th> AUDIT COUNT </th>
                        <th> % AVG </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $Sl = 1;
                        @$NOD = count(@$getSITEAuditData['allDistrict']);
                        for($i=0; $i<$NOD; $i++){  
//                            if(@$getPPEAuditData['allDistrict'][$i][0]->id != 182 ){
                        ?>
                        <td style="border:1px solid #000 !important;"><?php echo $Sl; ?></td>
                        <td bgcolor="#B9CDE5" style="border:1px solid #000 !important;"><?php echo @$getSITEAuditData['allDistrict'][$i][0]->name; ?></td>
                        <?php if(@$getSITEAuditData['allDistrict'][$i][0]->id == 10){ ?>
                        <!--P-SET-->
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffdd99"><?php echo $SITE_PSET_UNIT = 'NA'; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffb3ff"><?php echo $P_C1 = 'NA'; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffff99"><?php echo $P_D1 = 'NA'; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#b3ffff"><?php echo $P_E1 = 'NA'; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffaa80"><?php echo $P_F1 = 'NA'; ?></td>
                        <?php }else{ ?>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffdd99"><?php echo $SITE_PSET_UNIT = @$getSITEAuditData['P_SET_UNIT'][$i]; @$TOT_SITE_PSET_UNIT += $SITE_PSET_UNIT; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffb3ff"><?php echo $P_C1 = round(@$getSITEAuditData['P_SET_PREV'][$i]['totaldiv'],2);@$P_C1_TOT = @$P_C1_TOT + $P_C1;?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffff99"><?php echo $P_D1 = round(@$getSITEAuditData['P_SET_PREV'][$i]['score'],2); ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#b3ffff"><?php echo $P_E1 = round(@$getSITEAuditData['P_SET_CURRENT'][$i]['totaldiv'],2);@$P_E1_TOT = @$P_E1_TOT + $P_E1;?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffaa80"><?php echo $P_F1 = round(@$getSITEAuditData['P_SET_CURRENT'][$i]['score'],2); ?></td>
                        <?php } ?>
                        <td style="border:1px solid #000 !important;"></td>
                        <!--C-SET-->
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffdd99"><?php echo $SITE_CSET_UNIT = @$getSITEAuditData['C_SET_UNIT'][$i]; @$TOT_SITE_CSET_UNIT += $SITE_CSET_UNIT; ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffb3ff"><?php echo $C_C1 = round(@$getSITEAuditData['C_SET_PREV'][$i]['totaldiv'],2);@$C_C1_TOT = @$C_C1_TOT + $C_C1;?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffff99"><?php echo $C_D1 = round(@$getSITEAuditData['C_SET_PREV'][$i]['score'],2); ?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#b3ffff"><?php echo $C_E1 = round(@$getSITEAuditData['C_SET_CURRENT'][$i]['totaldiv'],2);@$C_E1_TOT = @$C_E1_TOT + $C_E1;?></td>
                        <td style="border:1px solid #000 !important;" align="center" bgcolor="#ffaa80"><?php echo $C_F1 = round(@$getSITEAuditData['C_SET_CURRENT'][$i]['score'],2); ?></td>
                    </tr>
                    <?php $Sl++;  
                            //}
                        } ?>
                     <tr bgcolor="#F2DCDB" style="border:1px solid #000 !important;"> 
                        <th style="border:1px solid #000 !important;" colspan="2">TOTAL / AVG</th>
                        <th style="border:1px solid #000 !important;"><?php echo $TOT_SITE_PSET_UNIT; ?></th>
                        <th style="border:1px solid #000 !important;"><?php echo $P_C1_TOT;?></th>
                        <th style="border:1px solid #000 !important;">--</th>
                        <th style="border:1px solid #000 !important;"><?php echo $P_E1_TOT; ?></th>
                        <th style="border:1px solid #000 !important;">--</th>
                        <th style="border:1px solid #000 !important;">--</th>
                        <th style="border:1px solid #000 !important;"><?php echo $TOT_SITE_CSET_UNIT; ?></th>
                        <th style="border:1px solid #000 !important;"><?php echo $C_C1_TOT; ?></th>
                        <th style="border:1px solid #000 !important;">--</th>
                        <th style="border:1px solid #000 !important;"><?php echo $C_E1_TOT; ?></th>
                        <th style="border:1px solid #000 !important;">--</th>
                        </tr>
                </tbody>
            </table>
        <div class="print1" style="float: left; border:1px solid #000; padding:3px 8px;">
            NA : NOT APPLICABLE</td>
        </div>
        <div class="print1" style="float: right; border:1px solid #000; padding:3px 8px;">
                        NOTE : P-Sets including Call Centres</td>
                    </div>
        </div>
    <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
        </div>
</div>
        <?php } ?>
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

$(document).ready(function(){
    $("#ExportExcel").click(function(e){
        var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html()); 
        window.open(path);
        e.preventDefault();
    });
});
    function PrintDiv()
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title>SURAKSHA BARTA SCORES STAT</title>');
    mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {padding:10px 2px; background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}body{margin:0;padding:0;}.table-siteAudit{margin-top:220px;}</style></head><body>');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    mywindow.close();
    return true;
}
 </script>