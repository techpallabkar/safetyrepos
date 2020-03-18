<?php 
$getQuestionMstGrp = $beanUi->get_view_data("getQuestionMstGrp");
$mergedGroupMaster = $beanUi->get_view_data("mergedGroupMaster");
$mrggroupauditdata = $beanUi->get_view_data("mrggroupauditdata");
?>
<form name="audit_scoresheet" id="audit_scoresheet" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
            <input type="hidden" name="scoredata[activity_type_id]" value="<?php echo $activity_id; ?>" />
            <input type="hidden" name="auditid" id="auditId" value="<?php echo $auditId; ?>">
            <input type="hidden" name="page_no" value="<?php echo $page; ?>" />
            <input type="hidden" name="fromdate_s" value="<?php echo $fromdate_s; ?>" />
            <input type="hidden" name="todate_s" value="<?php echo $todate_s; ?>" />
            <input type="hidden" name="activity_no_s" value="<?php echo $activity_no_s; ?>" />
            <input type="hidden" name="search_title_s" value="<?php echo $search_title_s; ?>" />
            <input type="hidden" name="status_id_s" value="<?php echo $status_id_s; ?>" />
            <input type="hidden" name="districtid_s" value="<?php echo $districtid_s; ?>" />
            <input type="hidden" name="_action" value="updateScoreGeneration" class="btn btn-sm btn-success" />
            <div class="freeze-container"> 
                <div class="row">
                    <div class="col-sm-2">
                        <button type="submit" name="prevtab" class="btn btn-warning btn-xs" value="Previous Tab" text="Previous Tab"><i class="fa fa-arrow-left"></i> Previous Tab</button>
                    </div>
                    <div class="col-sm-8 text-center">
                        <?php 
                            if($auditData[0]->status_id == 1){
                                echo '<label class="text-primary"><strong>Draft</strong></label> &nbsp; &nbsp;'; 
                            } else if($auditData[0]->status_id == 2){
                                echo '<label class="text-danger"><strong>Final Submit</strong></label> &nbsp; &nbsp;';
                            } else if($auditData[0]->status_id == 3){
                                echo '<label class="text-success"><strong>Approve & Publish</strong></label> &nbsp; &nbsp;';
                            } else if($auditData[0]->status_id == 4) {
                                echo '<label class="text-warning"><strong>Approve & Unpublish</strong></label>';
                            }
                        ?>
                        <button type="submit" class="btn btn-primary" id="saveTab2"><i class="fa fa-save"></i> Submit</button>
                        <a href="<?php echo page_link("activity/index.php?activity_id= $activity_id ");?>" onclick="return confirm('Are you sure to cancel this page ?')" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Cancel</a>
                    </div>
                    <div class="col-sm-2 text-right">
                        <button type="submit" name="nexttab" class="btn btn-warning btn-xs" value="Next Tab"  text="Next Tab">Next Tab <i class="fa fa-arrow-right"></i></button>   
                    </div>
                </div>
                <hr style="margin:5px 0;border-top:#999 solid 1px;">
                <table id="" class="table table-striped table-bordered2 table-condensed responsive-utilities jambo_table bulk_action">
                    <thead>
                        <tr style="vertical-align: middle;">
                            <th style="line-height:25px;">Engineer (CESC)</th>       
                            <td class="text-center" style="width:6%;"><input type="text" class="mp"  name="engineer" id="engineer" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo $auditData[0]->no_engineer; ?>" style="width:100%;margin-bottom:0px;" /></td>
                            <th style="line-height:25px;">Supervisor (CESC)</th>
                            <td class="text-center" style="width:6%"><input type="text" class="mp" name="supervisor_cesc" id="supervisor_cesc" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo $auditData[0]->no_pset_supervisor; ?>" style="width:100%;margin-bottom:0px;" /></td>
                            <th style="line-height:25px;">Supervisor (C-Set)</th>
                            <td class="text-center" style="width:6%"><input type="text" class="mp" name="supervisor_cset" id="supervisor_cset" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo $auditData[0]->no_cset_supervisor; ?>" style="width:100%;margin-bottom:0px;" /></td>
                            <th style="line-height:25px;">Workmen</th>
                            <td class="text-center" style="width:6%"><input type="text" class="mp" name="technician" id="technician" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo $auditData[0]->no_technician; ?>" style="width:100%;margin-bottom:0px;"/></td>
                            <th style="line-height:25px;">Total man Power</th>
                            <td class="text-center" style="width:6%"><input type="hidden" id="tmp" /><span id="tmpspn"><?php echo $auditData[0]->total_manpower; ?></span></td>
<!--                                <td><button type="submit" class="btn btn-warning" id="saveTab2"><i class="fa fa-save"></i> Submit</button></td>-->
                        </tr>
                    </tbody>
                </table>
            </div>
              
            <div class="freeze-container2">
                <table id="" class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
                    <thead>
                        <tr>
                            <th style="width:5%;">Sl. No.</th>
                            <th style="width:29%;">Item</th>
                            <th colspan="2">Findings</th>
                            <th style="width:7%;">Full Marks</th>
                            <th style="width:9%;">Obtained Marks</th>
                            <th style="width:25%;">Remarks</th>
                        </tr>

                    </thead>
                </table>
            </div>
                    <script type="text/javascript">
                        $(document).ready(function(){
                          $(".mp").keyup(function(){
                              var mp1 = (($("#engineer").val() > 0) ? $("#engineer").val() : 0);
                              var mp2 = (($("#supervisor_cesc").val() > 0) ? $("#supervisor_cesc").val() : 0);
                              var mp3 = (($("#supervisor_cset").val() > 0) ? $("#supervisor_cset").val() : 0);
                              var mp4 = (($("#technician").val() > 0) ? $("#technician").val() : 0);
                              var tmp = parseInt(mp1)+parseInt(mp2)+parseInt(mp3)+parseInt(mp4);
                              $("#tmp").val(tmp);
                              $("#tmpspn").html(tmp);
                          });                         
                        });
                    </script>
                    <?php 
                            if (!empty($getQuestionSetDetails)) {
                                foreach ($getQuestionSetDetails as $skey => $svalue) {
                                echo    '<h4 class="text-center">'.(($auditData[0]->gradation_sheet_division_id==$svalue->division_id)? "$svalue->subheading":"").'</h4>
                                    <h5 class="text-center">'.(($auditData[0]->gradation_sheet_division_id==$svalue->division_id)? "$svalue->heading":"").' </h5>';
                             }
                            }
                            ?>
                    <table class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
                        <?php 
                        $appfulmarks=0;
                        $appobmarks=0;
                        $totalgrpmrk=array();
                        $totalungrpmrk=0;
                        $strgrp='';
                        $vp=0;
                        foreach ($getQuestion as $keynew => $valuenew) {
                            foreach ($valuenew as $keynew1 => $valuenew1) {
                                if($valuenew1->marged_group_master_id!=0){
                                 $totalgrpmrk[$valuenew1->marged_group_master_id][]=$valuenew1->full_marks;
                                 if($vp!=$keynew){
                                   $strgrp.=$keynew.',';
                                   $vp=$keynew;
                                 }                                 
                                }else{
                                 $totalungrpmrk+=$valuenew1->full_marks;   
                                }
                            }                            
                        }
                        $strgrp=rtrim($strgrp,',');
                        
                        $mergedGroupScore=array();
                        $mergedGroupAvgScore=array();
                        $mergedGroupDtls=array();
                        if(!empty($mergedGroupMaster)){
                          foreach($mergedGroupMaster AS $kg1=>$vg1){                              
                              $mergedGroupAvgScore[$vg1->id]=(array_sum($totalgrpmrk[$vg1->id])/$vg1->marged_group_score);
                              $mergedGroupDtls[$vg1->id]=$vg1;
                          }  
                        }                        
                        $mgrgrp=array();                        
                        if(!empty($getQuestion)){ 
                            foreach($getQuestion as $key => $val){
                        ?>
                        <thead>
                            <tr>
                                <th rowspan="3" style="width:5%;">Sl. No.</th>
                                <th colspan="6"> <?php echo $getGroupIdByValue[$key]->group_name; ?>
                                <?php if($getGroupIdByValue[$key]->marged_group_master_id == 1){?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span><input type="checkbox" name="jsna[]" id="mstgrp_<?php echo $getGroupIdByValue[$key]->id; ?>" class="mstgrpna"> NA</span>
                            <?php } ?>
                                </th>
                            </tr>
                            <tr> 
                                <th style="width:29%;">Item</th>
                                <th colspan="2">Findings</th>
                                <th style="width:7%;">Full Marks</th>
                                <th style="width:9%;">Obtained Marks</th>
                                <th style="width:25%;">Remarks</th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            <?php 
                                $slno = 1;                               
                                foreach($getQuestion[$key] as $k => $v){
                                    $getGroupScore += $v->full_marks;
                                    $mgrgrp[$v->marged_group_master_id][$key]=$v->id;
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $slno; ?></td>
                                <td class="text-center" style="width:20%;"><?php echo $v->question; ?></td>
                                <input type="hidden"  name="groupName[]" value="<?php echo $getGroupIdByValue[$key]->group_name; ?>">
                                <input type="hidden"  name="groupId[]" value="<?php echo $v->group_id; ?>">
                                <input type="hidden"  name="groupTotal[]" value="<?php echo $getGroupIdByValue[$key]->total_score; ?>">
                                <input type="hidden"  name="setId[]" value="<?php echo $v->set_id; ?>">
                                <input type="hidden"  name="questionId[]" value="<?php echo $v->id; ?>">
                                <input type="hidden"  name="questionAudit[]" value="<?php echo $v->question; ?>">

                                <?php if($v->is_binary == 0){ ?>
                                
                                <td class="text-center">
                                    <p style="color:#3f75ff;font-size:12px;text-align:left;font-weight:600;margin-bottom:0px;">Req
                                        <select id="stdqnt<?php echo $v->id; ?>" class="width100 reqchg_<?php echo $getGroupIdByValue[$key]->id; ?> stdqntvalgn" name="stdQty[<?php echo $v->id; ?>]" style="width: 60% !important; float: right;">
                                        <option value="">-Select-</option>
                                        <?php foreach($getOptinIdByValue[$v->st_qnt_option_id] as $l => $k){ 
                                            if($k->option_value != '0'){?>
                                        <option value="<?php echo $k->option_value; ?>" <?php echo (($auditScore[$v->id]->standerd_qnt==$k->option_value)?'selected':''); ?>><?php echo $k->option_value; ?></option>
                                            <?php } } ?>    
                                    </select>
                                        </p>
                                </td>
                                <td class="text-center">
                                    <p style="color:#3f75ff;font-size:12px;text-align:left;font-weight:600;margin-bottom:0px;">Avl
                                    <select id="avlqnt<?php echo $v->id; ?>" class="width100 avlchg_<?php echo $getGroupIdByValue[$key]->id; ?> avlqntvalgn" name="avalQty[<?php echo $v->id; ?>]" style="width: 60% !important; float: right;">
                                        <option value="">-Select-</option>                                        
                                        <?php foreach($getOptinIdByValue[$v->avl_qnt_option_id] as $m => $n){ ?>
                                        <option value="<?php echo $n->option_value; ?>" <?php echo (($auditScore[$v->id]->available_qnt==$n->option_value)?'selected':''); ?>><?php echo $n->option_value; ?></option>
                                        <?php } ?>
                                    </select>
                                        </p>
                                </td>
                                <?php } else { ?>
                                <td class="text-center">
                                    <p style="color:#3f75ff;font-size:12px;text-align:left;font-weight:600;margin-bottom:0px;">Observation</p>
                                </td>
                                <td class="text-center">
                                    <select id="obrqnt<?php echo $v->id; ?>" class="width100 obschg_<?php echo $getGroupIdByValue[$key]->id; ?> obrqntvalgn" name="observeScore[<?php echo $v->id; ?>]">
                                        <option value="">-Select-</option>
                                        <?php foreach($getOptinIdByValue[$v->binary_option_id] as $o => $p){ ?>
                                        <option value="<?php echo $p->option_value; ?>" <?php echo (($auditScore[$v->id]->observation==$p->option_value)?'selected':''); ?>><?php echo $p->option_value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <?php } ?>
                                <?php $groupvag=($v->marged_group_master_id!=0?$mergedGroupAvgScore[$v->marged_group_master_id]:1); ?>
                                <td class="text-center"><input type="hidden" class="fullmarks<?php echo $key; ?>" id="fullmarks<?php echo $v->id; ?>" value="<?php echo (($auditScore[$v->id]->full_marks)/$groupvag); ?>"  name="fullMarks[]"/><span id="fullmarksspn<?php echo $v->id; ?>"><?php echo $v->full_marks; ?></span></td>
                                <td class="text-center"><input type="hidden" class="obtainmarks<?php echo $key; ?>" id="obtainmarks<?php echo $v->id; ?>" value="<?php echo (($auditScore[$v->id]->obtained_marks)/$groupvag); ?>"  name="obtainMarks[]"/><span id="obtainmarksspn<?php echo $v->id; ?>"><?php echo $auditScore[$v->id]->obtained_marks; ?></span></td>
                                <td><input type="hidden" class="avggrp<?php echo $key; ?>" id="avggrp<?php echo $v->id; ?>" value="<?php echo ($v->marged_group_master_id!=0?$mergedGroupAvgScore[$v->marged_group_master_id]:1); ?>" name="msgavggroup[]"/>
                                    <textarea class="width100 height50" rows="2"  name="remarks[]"><?php echo $auditScore[$v->id]->remarks; ?></textarea>
                                <input type="hidden" id="groupavg<?php echo $v->id; ?>" value="<?php echo $v->marged_group_master_id; ?>" name="mergedgroup[]"/>
                                <input type="hidden" class="fullmarksgrp<?php echo $v->marged_group_master_id; ?>" id="fullmarksgrp<?php echo $v->id; ?>" value="<?php echo (($auditScore[$v->id]->full_marks)/$groupvag); ?>"/>
                                <input type="hidden" class="obtainmarksgrp<?php echo $v->marged_group_master_id; ?>" id="obtainmarksgrp<?php echo $v->id; ?>" value="<?php echo (($auditScore[$v->id]->obtained_marks)/$groupvag); ?>"/>
                                </td>
                            </tr>
                            <?php //if(){ ?>
                                    
                                <?php //} ?>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                  $("#stdqnt<?php echo $v->id; ?>").change(function(){
                                      var std = $(this).find(":selected").val();
                                      var avl = $("#avlqnt<?php echo $v->id; ?>").find(":selected").val();
                                      var fullmarks = (($('#fullmarksspn<?php echo $v->id; ?>').html() > 0) ? $('#fullmarksspn<?php echo $v->id; ?>').html() : 0);
                                      var grpavg=parseFloat($("#avggrp<?php echo $v->id; ?>").val());
                                      var atrarr='<?php echo $strgrp?>';
                                      var grpid=$("#groupavg<?php echo $v->id; ?>").val();
                                      if(std == 'NA'){
                                        $("#avlqnt<?php echo $v->id; ?>").val(std);
                                        $("#obtainmarks<?php echo $v->id; ?>").val(0);  
                                        $("#fullmarks<?php echo $v->id; ?>").val(0);  
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html('NA'); 
                                        $("#avlqnt<?php echo $v->id; ?>").prop("disabled", true);
                                      } else if((Number(std) <= Number(avl)) && std!='') {
                                        $("#fullmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));  
                                        $("#obtainmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));  
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html(fullmarks);  
                                        $("#avlqnt<?php echo $v->id; ?>").prop("disabled", false);
                                      } else if(std == '' && avl =='') {
                                        $("#fullmarks<?php echo $v->id; ?>").val(fullmarks);  
                                        $("#obtainmarks<?php echo $v->id; ?>").val(0);  
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html(0);  
                                        $("#avlqnt<?php echo $v->id; ?>").prop("disabled", false);
                                      }else {
                                          if(avl != 'NA'){
                                            var calmarks = (fullmarks/std)*avl;
                                            $("#fullmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));
                                            $("#obtainmarks<?php echo $v->id; ?>").val((calmarks/grpavg));  
                                            $("#obtainmarksspn<?php echo $v->id; ?>").html(calmarks.toFixed(2));  
                                            $("#avlqnt<?php echo $v->id; ?>").prop("disabled", false);
                                          } else {
                                            $("#obtainmarks<?php echo $v->id; ?>").val(0);  
                                            $("#obtainmarksspn<?php echo $v->id; ?>").html(0);  
                                            $("#avlqnt<?php echo $v->id; ?>").prop("disabled", false);
                                            $("#avlqnt<?php echo $v->id; ?>").val('');
                                          }
                                      }
                                      $("#fullmarksgrp<?php echo $v->id; ?>").val($("#fullmarks<?php echo $v->id; ?>").val());  
                                      $("#obtainmarksgrp<?php echo $v->id; ?>").val($("#obtainmarks<?php echo $v->id; ?>").val());
                                       
                                      var sumfull = 0;
                                      $(".fullmarks<?php echo $key; ?>").each(function(){
                                          sumfull += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#gs<?php echo $key; ?>").val(sumfull);
                                      var sumobt = 0;
                                      $(".obtainmarks<?php echo $key; ?>").each(function(){
                                          sumobt += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#gso<?php echo $key; ?>").val(sumobt);
                                      var tgso = (sumobt*grpavg).toFixed(2) +' / '+ (sumfull*grpavg).toFixed(2);
                                      if(sumfull > 0){
                                           var tpgso = ((sumobt/sumfull) * 100);
                                      } else {
                                           var tpgso = 0.00;
                                      }
                                      $("#gsospn<?php echo $key; ?>").html(tgso);
                                      $("#gsop<?php echo $key; ?>").val(tpgso.toFixed(2));
                                      $("#gsopspn<?php echo $key; ?>").html(tpgso.toFixed(2));
                                       /**/                                      
                                      var sumfull1 = 0;
                                      $(".fullmarksgrp"+grpid).each(function(){
                                          sumfull1 += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      var sumobt1 = 0;
                                      $(".obtainmarksgrp"+grpid).each(function(){
                                          sumobt1 += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      var tgso1 = sumobt1.toFixed(2) +' / '+ sumfull1.toFixed(2);
                                      if(sumfull1 > 0){
                                           var tpgso1 = ((sumobt1/sumfull1) * 100);
                                      } else {
                                           var tpgso1 = 0.00;
                                      }
                                      $("#grpavgspanmarks"+grpid).html(tgso1);                                      
                                      $("#grpavgspan"+grpid).html(tpgso1.toFixed(2));
                                      $("#groupobt"+grpid).val(sumobt1.toFixed(2));                                      
                                      $("#groupfull"+grpid).val(sumfull1.toFixed(2));
                                      /**/
                                      var sumfullscore = 0;
                                      $(".gsscore").each(function(){
                                          sumfullscore += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      $("#aplgroscore").val(sumfullscore.toFixed(2));
                                      $("#aplgroscorespn").html(sumfullscore.toFixed(2));
                                      var sumobtscore = 0;
                                      $(".gsoscore").each(function(){
                                          sumobtscore += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#scoreobt").val(sumobtscore.toFixed(2));
                                      $("#scoreobtspn").html(sumobtscore.toFixed(2));
                                      if(sumfullscore > 0){
                                        var tpgsoscore = ((sumobtscore/sumfullscore) * 100);
                                      } else {
                                        var tpgsoscore = 0.00;  
                                      }
                                      $("#finalscore").val(tpgsoscore.toFixed(2));
                                      $("#finalscorespn").html(tpgsoscore.toFixed(2));
                                      
                                  });
                                  
                                  $("#avlqnt<?php echo $v->id; ?>").change(function(){
                                      var avl = $(this).find(":selected").val();
                                      var std = $("#stdqnt<?php echo $v->id; ?>").find(":selected").val();
                                      var fullmarks = (($('#fullmarksspn<?php echo $v->id; ?>').html() > 0) ? $('#fullmarksspn<?php echo $v->id; ?>').html() : 0);
                                      var grpavg=parseFloat($("#avggrp<?php echo $v->id; ?>").val());
                                      var atrarr='<?php echo $strgrp?>';
                                      var grpid=$("#groupavg<?php echo $v->id; ?>").val();
                                      if(avl == 'NA'){
                                        $("#obtainmarks<?php echo $v->id; ?>").val(0);
                                        $("#fullmarks<?php echo $v->id; ?>").val(0);
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html('NA'); 
                                        $("#stdqnt<?php echo $v->id; ?>").val(avl)
                                        $("#stdqnt<?php echo $v->id; ?>").prop("disabled", true);
                                      } else if((Number(std) <= Number(avl)) && avl!='') { 
                                        $("#fullmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));  
                                        $("#obtainmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));  
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html(fullmarks);  
                                        $("#stdqnt<?php echo $v->id; ?>").prop("disabled", false);
                                      } else if(std == '' && avl =='') { 
                                        $("#fullmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));  
                                        $("#obtainmarks<?php echo $v->id; ?>").val(0);  
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html(0);  
                                        $("#stdqnt<?php echo $v->id; ?>").prop("disabled", false);
                                      }else {
                                          if(std != 'NA'){
                                            var calmarks = (fullmarks/std)*avl; 
                                            $("#fullmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));
                                            $("#obtainmarks<?php echo $v->id; ?>").val((calmarks/grpavg));  
                                            $("#obtainmarksspn<?php echo $v->id; ?>").html(calmarks.toFixed(2));  
                                            $("#stdqnt<?php echo $v->id; ?>").prop("disabled", false);
                                          } else {
                                            $("#obtainmarks<?php echo $v->id; ?>").val(0);  
                                            $("#obtainmarksspn<?php echo $v->id; ?>").html(0);  
                                            $("#stdqnt<?php echo $v->id; ?>").prop("disabled", false);
                                            $("#stdqnt<?php echo $v->id; ?>").val('');
                                          }
                                      }
                                        $("#fullmarksgrp<?php echo $v->id; ?>").val($("#fullmarks<?php echo $v->id; ?>").val());  
                                       $("#obtainmarksgrp<?php echo $v->id; ?>").val($("#obtainmarks<?php echo $v->id; ?>").val());
                                       
                                      var sumfull = 0;
                                      $(".fullmarks<?php echo $key; ?>").each(function(){
                                          sumfull += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#gs<?php echo $key; ?>").val(sumfull);
                                      var sumobt = 0;
                                      $(".obtainmarks<?php echo $key; ?>").each(function(){
                                          sumobt += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#gso<?php echo $key; ?>").val(sumobt);
                                      var tgso = (sumobt*grpavg).toFixed(2) +' / '+ (sumfull*grpavg).toFixed(2);
                                      if(sumfull > 0){
                                           var tpgso = ((sumobt/sumfull) * 100);
                                      } else {
                                           var tpgso = 0.00;
                                      }
                                      $("#gsospn<?php echo $key; ?>").html(tgso);
                                      $("#gsop<?php echo $key; ?>").val(tpgso.toFixed(2));
                                      $("#gsopspn<?php echo $key; ?>").html(tpgso.toFixed(2));
                                      
                                       /**/                                      
                                      var sumfull1 = 0;
                                      $(".fullmarksgrp"+grpid).each(function(){
                                          sumfull1 += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      var sumobt1 = 0;
                                      $(".obtainmarksgrp"+grpid).each(function(){
                                          sumobt1 += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      var tgso1 = sumobt1.toFixed(2) +' / '+ sumfull1.toFixed(2);
                                      if(sumfull1 > 0){
                                           var tpgso1 = ((sumobt1/sumfull1) * 100);
                                      } else {
                                           var tpgso1 = 0.00;
                                      }
                                      $("#grpavgspanmarks"+grpid).html(tgso1);                                      
                                      $("#grpavgspan"+grpid).html(tpgso1.toFixed(2));
                                      $("#groupobt"+grpid).val(sumobt1.toFixed(2));                                      
                                      $("#groupfull"+grpid).val(sumfull1.toFixed(2));
                                      /**/
                                      var sumfullscore = 0;
                                      $(".gsscore").each(function(){
                                          sumfullscore += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      $("#aplgroscore").val(sumfullscore.toFixed(2));
                                      $("#aplgroscorespn").html(sumfullscore.toFixed(2));
                                      var sumobtscore = 0;
                                      $(".gsoscore").each(function(){
                                          sumobtscore += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#scoreobt").val(sumobtscore.toFixed(2));
                                      $("#scoreobtspn").html(sumobtscore.toFixed(2));
                                      if(sumfullscore > 0){
                                        var tpgsoscore = ((sumobtscore/sumfullscore) * 100);
                                      } else {
                                        var tpgsoscore = 0.00;  
                                      }
                                      $("#finalscore").val(tpgsoscore.toFixed(2));
                                      $("#finalscorespn").html(tpgsoscore.toFixed(2));
                                      
                                  });
                                  
                                  $("#obrqnt<?php echo $v->id; ?>").change(function(){                                      
                                      var obr = $(this).find(":selected").val();
                                      var fullmarks = (($('#fullmarksspn<?php echo $v->id; ?>').html() > 0) ? $('#fullmarksspn<?php echo $v->id; ?>').html() : 0);
                                      var grpavg=parseFloat($("#avggrp<?php echo $v->id; ?>").val());
                                      var atrarr='<?php echo $strgrp?>';
                                      var grpid=$("#groupavg<?php echo $v->id; ?>").val();                                      
                                      if(obr == 'NA'){
                                        $("#obtainmarks<?php echo $v->id; ?>").val(0);
                                        $("#fullmarks<?php echo $v->id; ?>").val(0);
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html('NA');
                                      } else if(obr == 'DONE' || obr == 'YES') {
                                        $("#fullmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));  
                                        $("#obtainmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));  
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html(fullmarks);
                                      } else {
                                        $("#obtainmarks<?php echo $v->id; ?>").val(0);
                                        $("#fullmarks<?php echo $v->id; ?>").val((fullmarks/grpavg));
                                        $("#obtainmarksspn<?php echo $v->id; ?>").html(0);
                                      }
                                       $("#fullmarksgrp<?php echo $v->id; ?>").val($("#fullmarks<?php echo $v->id; ?>").val());  
                                       $("#obtainmarksgrp<?php echo $v->id; ?>").val($("#obtainmarks<?php echo $v->id; ?>").val());
                                       
                                      var sumfull = 0;
                                      $(".fullmarks<?php echo $key; ?>").each(function(){
                                          sumfull += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#gs<?php echo $key; ?>").val(sumfull);
                                      var sumobt = 0;
                                      $(".obtainmarks<?php echo $key; ?>").each(function(){
                                          sumobt += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#gso<?php echo $key; ?>").val(sumobt);
                                      var tgso = (sumobt*grpavg).toFixed(2) +' / '+ (sumfull*grpavg).toFixed(2);
                                      if(sumfull > 0){
                                           var tpgso = ((sumobt/sumfull) * 100);
                                      } else {
                                           var tpgso = 0.00;
                                      }
                                      $("#gsospn<?php echo $key; ?>").html(tgso);
                                      $("#gsop<?php echo $key; ?>").val(tpgso.toFixed(2));
                                      $("#gsopspn<?php echo $key; ?>").html(tpgso.toFixed(2));
                                      /**/                                      
                                      var sumfull1 = 0;
                                      $(".fullmarksgrp"+grpid).each(function(){
                                          sumfull1 += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      var sumobt1 = 0;
                                      $(".obtainmarksgrp"+grpid).each(function(){
                                          sumobt1 += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      var tgso1 = sumobt1.toFixed(2) +' / '+ sumfull1.toFixed(2);
                                      if(sumfull1 > 0){
                                           var tpgso1 = ((sumobt1/sumfull1) * 100);
                                      } else {
                                           var tpgso1 = 0.00;
                                      }
                                      $("#grpavgspanmarks"+grpid).html(tgso1);                                      
                                      $("#grpavgspan"+grpid).html(tpgso1.toFixed(2));
                                      $("#groupobt"+grpid).val(sumobt1.toFixed(2));                                      
                                      $("#groupfull"+grpid).val(sumfull1.toFixed(2));
                                      /**/
                                      var sumfullscore = 0;
                                      $(".gsscore").each(function(){
                                          sumfullscore += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      
                                      $("#aplgroscore").val(sumfullscore.toFixed(2));
                                      $("#aplgroscorespn").html(sumfullscore.toFixed(2));
                                      var sumobtscore = 0;
                                      $(".gsoscore").each(function(){
                                          sumobtscore += ((parseFloat($(this).val()) > 0) ? parseFloat($(this).val()) : 0);  // Or this.innerHTML, this.innerText
                                      });
                                      $("#scoreobt").val(sumobtscore.toFixed(2));
                                      $("#scoreobtspn").html(sumobtscore.toFixed(2));
                                      if(sumfullscore > 0){
                                        var tpgsoscore = ((sumobtscore/sumfullscore) * 100);
                                      } else {
                                        var tpgsoscore = 0.00;  
                                      }
                                      $("#finalscore").val(tpgsoscore.toFixed(2));
                                      $("#finalscorespn").html(tpgsoscore.toFixed(2));
                                      
                                  });
                                    
                                  
                                });
                            </script>
                            <?php $slno++; 
                            $mstgrid=$v->marged_group_master_id;
                            $appfulmarks+=(($auditScore[$v->id]->full_marks)/$groupvag);
                            $appobmarks+=(($auditScore[$v->id]->obtained_marks)/$groupvag);
                            } ?>
                            <?php /*$appfulmarks+=$auditScoreGroup[$key]->applicable_full_marks;
                            $appobmarks+=$auditScoreGroup[$key]->total_obtained_marks;*/
                            ?>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2" class="text-right">Group Score Obtained</td>
                                <td colspan="2"class="text-center">
                                    <input type="hidden" class="gsoscore" id="gso<?php echo $key; ?>" value="<?php echo (($auditScoreGroup[$key]->total_obtained_marks)/$groupvag); ?>" />
                                    <input type="hidden" class="gsscore" id="gs<?php echo $key; ?>" value="<?php echo (($auditScoreGroup[$key]->applicable_full_marks)/$groupvag); ?>" />
                                    <input type="hidden" id="gsop<?php echo $key; ?>" />                                    
                                    <span id="gsospn<?php echo $key; ?>"><?php echo $auditScoreGroup[$key]->total_obtained_marks; ?>/<?php echo $auditScoreGroup[$key]->applicable_full_marks; ?></span>
                                </td>
                                <td class="text-center"><span id="gsopspn<?php echo $key; ?>"><?php echo $auditScoreGroup[$key]->percentage_obtanined; ?></span>%</td>
                            </tr>
                            <tr>
                                <td colspan="7" style="background:#fff;border: 0;">&nbsp;</td>
                            </tr>
                            <?php if(!empty($getQuestionMstGrp[$mstgrid]) && (count($mgrgrp[$mstgrid]))==count($getQuestionMstGrp[$mstgrid])){?>
                            <tr>                                
                                <td colspan="2"></td>
                                <td colspan="2" class="text-right">Group Score Obtained</td>
                                <td colspan="2"class="text-center">
                                    <span id="grpavgspanmarks<?php echo $mstgrid; ?>"><?php echo $mrggroupauditdata[$mstgrid]->marged_group_score; ?>/<?php echo $mrggroupauditdata[$mstgrid]->marged_group_total_score; ?></span>
                                    <input type="hidden" class="gsoscoregroup" id="groupobt<?php echo $mstgrid; ?>" name="groupobt[]" value="<?php echo $mrggroupauditdata[$mstgrid]->marged_group_score; ?>"/>
                                    <input type="hidden" class="gsoscoregroup" id="groupfull<?php echo $mstgrid; ?>" name="groupfull[]" value="<?php echo $mrggroupauditdata[$mstgrid]->marged_group_total_score; ?>"/>
                                    <input type="hidden" class="gsscoregroup" value="<?php echo $mstgrid; ?>" name="mstgroupid[]"/>
                                    <input type="hidden" class="gsscoregroup" value="<?php echo $mergedGroupDtls[$mstgrid]->marged_group_name; ?>" name="mstgroupname[]"/>
                                    <input type="hidden" class="gsscoregroup" value="<?php echo $mergedGroupDtls[$mstgrid]->marged_group_score; ?>" name="mstgroupfullmarks[]"/>
                                </td>
                                <?php
                                    $mrggrppercent='';
                                    if(!empty($mrggroupauditdata[$mstgrid])){
                                        $mrggrppercent=(($mrggroupauditdata[$mstgrid]->marged_group_score/$mrggroupauditdata[$mstgrid]->marged_group_total_score)*100);
                                    }
                                ?>
                                <td class="text-center"><span id="grpavgspan<?php echo $mstgrid; ?>"><?php echo round($mrggrppercent, 2); ?></span>%</td>                            
                            </tr>
                            <tr>
                                <td colspan="7" style="background:#fff;border: 0;">&nbsp;</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                            <?php } } ?>
                    </table>
                    <br/>
                    <table class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
                        <tr>
                            <td class="text-right" style="width: 52%;">Score Obtained</td>
                            <td style="width: 10%;" class="text-center">
                                <input type="hidden"  name="scoreobt" id="scoreobt" value="<?php echo $appobmarks; ?>" />
                                <input type="hidden" name="aplgroscore" id="aplgroscore" value="<?php echo $appfulmarks; ?>" />
                                <input type="hidden" name="finalscore" id="finalscore" value="<?php echo (round((($appobmarks/$appfulmarks)*100), 2)); ?>" />
                                <input type="hidden" name="groupTotalScore" value="<?php echo $getGroupScore; ?>">
                                <span id="scoreobtspn"><?php echo sprintf('%0.2f',round($appobmarks, 2)); ?></span>
                            </td>
                            <td style="width: 38%;"></td>
                        </tr>
                        <tr>
                            <td class="text-right">Applicable Group Score</td>
                            <td class="text-center"><span id="aplgroscorespn"><?php echo sprintf('%0.2f',round($appfulmarks, 2)); ?></span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-right"><b>Final Score (%)</b></td>
                            <td class="text-center"><b><span id="finalscorespn"><?php echo sprintf('%0.2f',(round((($appobmarks/$appfulmarks)*100), 2))); ?>%</span></b></td>
                            <td></td>
                        </tr>
                    </table>
                    <br />
                    <div class="holder">
                        <!--<label for="status_id">Activity status</label>-->
                        <?php //show($post_status); ?>
                        <select style="visibility: hidden;" name="data[status_id]" id="audit_status_id">
                        <?php
                        
//                        $created_by = $beanUi->get_view_data("created_by");
//                        $role_id = $controller->get_auth_user("role_id");
                        if (!empty($post_status)) {
                            if ($role_id == 1) {
                                $status = array("Draft", "Final Submit");
                            } else if ($role_id == 2) {
                                $status = array("Draft");
                            } else if ($role_id == 3) {
                                $status = array("Draft", "Final Submit", "Approve & Publish", "Approve & Unpublish");
                            }
                            $status_id = $beanUi->get_view_data("status_id");
                            foreach ($post_status as $statusrow) {

                                if (in_array($statusrow->status_name, $status)) {
                                    echo '<option value="' . $statusrow->id . '" '.(($auditData[0]->status_id==$statusrow->id)?'selected':'').'>' . $statusrow->status_name . '</option>' . "\n";
                                }
                            }
                        }
                        ?>
                </select>
                <div id="status_id_error"><?php echo $beanUi->get_error('status_id'); ?></div>
                    </div>
<!--                    <br />
                    <p class="text-center">
                        <button type="submit" class="btn btn-primary" id="saveTab2"><i class="fa fa-save"></i> Submit</button>
                        <a href="<?php echo page_link("activity/index.php?activity_id= $activity_id ");?>" onclick="return confirm('Are you sure to cancel this page ?')" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Cancel</a>
                    </p>
                    <div class="clearfix">
                    <div class="col-xs-6">
                        <button type="submit" name="prevtab" class="btn btn-warning btn-xs" value="Previous Tab" text="Previous Tab"><i class="fa fa-arrow-left"></i> Previous Tab</button>
                    </div>
                     <div class="col-xs-6 text-right">
                    <button type="submit" name="nexttab" class="btn btn-warning btn-xs" value="Next Tab"  text="Next Tab">Next Tab <i class="fa fa-arrow-right"></i></button>   
                
                     </div>
                </div>-->
                    </form>

<script type="text/javascript">
    $(document).ready(function(e){
         $(".mstgrpna").click(function(e){
            if($(this).prop("checked") == true){
                var gpid=$(this).prop("id");
                var gpar=gpid.split("_");
                var opid=gpar[1];
                $('.reqchg_'+opid).val('NA');
                $('.avlchg_'+opid).val('NA');
                $('.obschg_'+opid).val('NA');
                $('.reqchg_'+opid).change();
                $('.avlchg_'+opid).change();
                $('.obschg_'+opid).change();
            }else if($(this).prop("checked") == false){
                var gpid=$(this).prop("id");
                var gpar=gpid.split("_");
                var opid=gpar[1];
                $('.reqchg_'+opid).val('');
                $('.avlchg_'+opid).val('');
                $('.obschg_'+opid).val('');
                $('.reqchg_'+opid).change();
                $('.avlchg_'+opid).change();
                $('.obschg_'+opid).change();
            }
        });
    })
</script>