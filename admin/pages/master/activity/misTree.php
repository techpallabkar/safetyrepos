
<?php
if (file_exists("../../lib/var.inc.php")) 
    require_once( "../../lib/var.inc.php" );
    $controller = load_controller("ReportController");
    $controller->doAction();
    $beanUi     = $controller->beanUi;
    $page       = $beanUi->get_view_data("page");
//    $allFinancialYear           = $beanUi->get_view_data("allFinancialYear");
//    $getAllMonthofFinancialYear = $beanUi->get_view_data("getAllMonthofFinancialYear");
//    $financial_year_id          = $beanUi->get_view_data("financial_year_id");
    $allActivity                = $beanUi->get_view_data("allActivity");
    $actvalue                = $beanUi->get_view_data("actvalue") ? $beanUi->get_view_data("actvalue") : "";
  
//    $parentDivision             = $beanUi->get_view_data("parentDivision");
//    $misData                    = $beanUi->get_view_data("misData");
//    $allUsers                   = $beanUi->get_view_data("allUsers");
//    $totalCountArray            = $beanUi->get_view_data("totalCountArray");
//    $workshopReport             = $beanUi->get_view_data("workshop");
//    $workshopTotal              = $beanUi->get_view_data("workshopTotal");
//    $communicationReport        = $beanUi->get_view_data("commMeeting");
//    $safetydaysReport           = $beanUi->get_view_data("safetyDays");
//    $trainingReport             = $beanUi->get_view_data("training");
//    $siteauditReport            = $beanUi->get_view_data("siteAudit");
//    $incidentReport             = $beanUi->get_view_data("incident");
//    $ppeauditReport             = $beanUi->get_view_data("ppeAudit");
//    $safetyobsReport            = $beanUi->get_view_data("safeyObs");
//    $safeyObsLIneFunc           = $beanUi->get_view_data("safeyObsLIneFunc");
//    $alldatagen                 = $beanUi->get_view_data("alldatagen");
//    $alldatadist                = $beanUi->get_view_data("alldatadist");
//    $financial_year_for_select  = $beanUi->get_view_data("financial_year_for_select");
//    $financial_year             = ($beanUi->get_view_data("financial_year") ? $beanUi->get_view_data("financial_year") : "" );
//    $individualworkshop         = $beanUi->get_view_data("individualworkshop");
//    $individualcommmeeting      = $beanUi->get_view_data("individualcommmeeting");
//    $individualtraining         = $beanUi->get_view_data("individualtraining");
//    $individualsafetyDays       = $beanUi->get_view_data("individualsafetyDays");
    $treeDivisionData       = $beanUi->get_view_data("treeDivisionData");
    //show($treeDivisionData);
    $controller->get_header();
    $site_root_url = dirname(url());
    
?>


<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">SAFETY PORTAL - MIS TREE</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <!--<img src="<?php echo url('assets/images/loading.gif') ?>" id="asddd" style="display:none;width:100px;">-->
    <form id="abc" method="post">
        <input type="hidden" name="action" value="treesubmit" />
        <input style=" display: none;" type="submit" id="btnsub" value="Generate Mis Report" class="btn btn-primary btn-sm" />
    </form>
    <?php if(!empty($treeDivisionData)) { ?>
    <div style="width:100%;overflow-x: scroll;">
        
    <table id="reporttbl" class="table table-bordered table-condensed table-responsive" style="width:100%;">
        <tr >
            <th rowspan="2" width="20%">DISTRICT</th>
            <th rowspan="2">+</th>
            <?php
            foreach ($allActivity as $rowdata) {
                if ($rowdata->id != 9 && $rowdata->id!=10) {
                    echo '<th class="bg-primary" colspan="2">' . $rowdata->activity_name . '</th>';
                }
            }
            ?>
        </tr>
        <tr>
            <th bgcolor="#EDEDED" >Individual</th>
            <th bgcolor="#337AB7" style="color:#fff;">Total</th>
            <th bgcolor="#EDEDED" style="">Individual</th>
            <th bgcolor="#337AB7" style="color:#fff;">Total</th>
            <th bgcolor="#EDEDED" style="">Individual</th>
            <th bgcolor="#337AB7" style="color:#fff;">Total</th>
            <th bgcolor="#EDEDED" style="">Individual</th>
            <th bgcolor="#337AB7" style="color:#fff;">Total</th>
            <th bgcolor="#EDEDED" style="">Individual</th>
            <th bgcolor="#337AB7" style="color:#fff;">Total</th>
            <th bgcolor="#EDEDED" style="">Individual</th>
            <th bgcolor="#337AB7" style="color:#fff;">Total</th>
            <th bgcolor="#EDEDED" style="">Individual</th>
            <th bgcolor="#337AB7" style="color:#fff;">Total</th>
            <th bgcolor="#EDEDED" style="">Individual</th>
            <th bgcolor="#337AB7" style="color:#fff;">Total</th>
        </tr>
        <?php
        $arrrr="";
        $arrColor = array("#F2F2F2", "#92D050", "#639add", "#f2984f", "#1ABB9C", "#cfa0ff", "#a1e4fc", "#eda56a");
        foreach ($treeDivisionData as $key => $rowdata) {
           $colorset = 'background-color:'.$arrColor[$rowdata->layer].';';
           $side = 'text-indent:'.($rowdata->layer*8).'px;text-align: justify;';
           
            if($key == 1 ) {
                
                echo ' <tr  style="font-weight:bold;'.$colorset.'" data-id="' . $key . '" data-index="0" data-parent="' . $rowdata->parent_id . '"  class="  childClass_' . $rowdata->parent_id . ' bg-info">'
                        . '<td><b>CESC</b></td>';
            } else {
                echo ' <tr style="'.$colorset.'" data-id="' . $key . '" data-index="0" data-parent="' . $rowdata->parent_id . '"  class="hidecls  childClass_' . $rowdata->parent_id . ' bg-info">'
                        . '<td style="'.$side.'">'.$rowdata->name.'</td>';
            }
            echo '<td class="plus"><i class="fa fa-plus 4x parentClass" style="cursor:pointer;"></i></td>
            <td>' . @$rowdata->itemCount->incidentIndividual . '</td>
            <td>' . @$rowdata->itemCount->incidentTotal . '</td>
            <td>' . @$rowdata->itemCount->auditIndividual . '</td>
            <td>' . @$rowdata->itemCount->auditTotal . '</td>
            <td>' . @$rowdata->itemCount->ppeauditIndividual . '</td>
            <td>' . @$rowdata->itemCount->ppeauditTotal . '</td>
            <td>' . @$rowdata->itemCount->trainIndividual . '</td>
            <td>' . @$rowdata->itemCount->trainTotal . '</td>
            <td>' . @$rowdata->itemCount->workshopIndividual . '</td>
            <td>' . @$rowdata->itemCount->workshopTotal . '</td>
            <td>' . @$rowdata->itemCount->commIndividual. '</td>
            <td>' . @$rowdata->itemCount->commTotal. '</td>
            <td>' . @$rowdata->itemCount->safobsIndividual. '</td>
            <td>' . @$rowdata->itemCount->safobsTotal. '</td>
            <td>' . @$rowdata->itemCount->safDayIndividual. '</td>
            <td>' . @$rowdata->itemCount->safDayTotal. '</td>            
            </tr>';
            
          
        }
        ?>
    </table>
        </div>
    
    <?php } ?>
    <hr />
</div>
<?php $controller->get_footer(); ?>

 <script>
    
        $(document).ready(function(){ 
           var treesubmit = "<?php echo  $actvalue ;?>"; 
          
            if(treesubmit ===""){
              $('#btnsub').click();
              $('#btnsub').hide();
                $('.background-loading').show();
                var ajaxdata={
                    "action" : "treesubmit"
                };
                $.ajax({
                    type: 'POST',
                    data: ajaxdata,
                    success: function(data) {
                        if(data) {
                        $('.background-loading').hide();
                          <?php   $actvalue  = ""; ?>
                        }
                    }

                });
//            $('#btnsub').click(function(){ alert("dfkjhdkj");
//                $('#btnsub').hide();
//                $('.background-loading').show();
//                var ajaxdata={
//                    "action" : "treesubmit"
//                };
//                $.ajax({
//                    type: 'POST',
//                    data: ajaxdata,
//                    success: function(data) {
//                        if(data) {
//                        $('.background-loading').hide();
//                        }
//                    }
//
//                });
//            });
      }
            
        $('.hidecls').hide();
           $(".parentClass").on('click',function(){
                var rowID = ($(this).parents("tr").data("id"));
                //alert($("#reporttbl tr.childClass_"+rowID).data("parent"));
                $("#reporttbl tr.childClass_"+rowID).fadeToggle();
                
                var rowvalue = $(this).parents("td").attr('class');                
                if(rowvalue == "plus") {
                    $(this).removeClass('fa-plus').addClass('fa-minus');
                    $(this).parents("td").attr("class","minus");    
                }
                if(rowvalue == "minus") {
                    $(this).removeClass('fa-minus').addClass('fa-plus');
                    $(this).parents("td").attr("class","plus");    
                    
                    $("#reporttbl tr.childClass_"+rowID).each(function(){
                        var rowID2 = $(this).data("id");
                        $(this).children("td").attr("class","plus");
                        $(this).children("td").children("i").removeClass('fa-minus').addClass('fa-plus');
                        $("#reporttbl tr.childClass_"+rowID2).hide();
                            $("#reporttbl tr.childClass_"+rowID2).each(function() {
                                var rowID3 = $(this).data("id");
                                $(this).children("td").attr("class","plus");
                                $(this).children("td").children("i").removeClass('fa-minus').addClass('fa-plus');
                                $("#reporttbl tr.childClass_"+rowID3).hide();
                                $("#reporttbl tr.childClass_"+rowID3).each(function() {
                                    var rowID4 = $(this).data("id");
                                    $(this).children("td").attr("class","plus");
                                    $(this).children("td").children("i").removeClass('fa-minus').addClass('fa-plus');
                                    $("#reporttbl tr.childClass_"+rowID4).hide();
                                    $("#reporttbl tr.childClass_"+rowID4).each(function() {
                                        var rowID5 = $(this).data("id");
                                        $(this).children("td").attr("class","plus");
                                        $(this).children("td").children("i").removeClass('fa-minus').addClass('fa-plus');
                                        $("#reporttbl tr.childClass_"+rowID5).hide();
                                            $("#reporttbl tr.childClass_"+rowID5).each(function() {
                                            var rowID6 = $(this).data("id");
                                            $(this).children("td").attr("class","plus");
                                            $(this).children("td").children("i").removeClass('fa-minus').addClass('fa-plus');
                                            $("#reporttbl tr.childClass_"+rowID6).hide();
                                                $("#reporttbl tr.childClass_"+rowID6).each(function() {
                                                    var rowID7 = $(this).data("id");
                                                    $(this).children("td").attr("class","plus");
                                                    $(this).children("td").children("i").removeClass('fa-minus').addClass('fa-plus');
                                                    $("#reporttbl tr.childClass_"+rowID7).hide();
                                                });
                                        });
                                    });
                                });
                            });
                    });
                }
                
                
                //$("#reporttbl tr.childClass_"+rowID).addClass(''+rowID+'');
              
                
                    
                });
                
           
         
        });
      
        </script>
        
   
        <div class="background-loading"></div>
</body>
</html>

