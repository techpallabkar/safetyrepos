
<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$allFinancialYear = $beanUi->get_view_data("allFinancialYear");
$getAllMonthofFinancialYear = $beanUi->get_view_data("getAllMonthofFinancialYear");

$financial_year = ($beanUi->get_view_data("financial_year") ? $beanUi->get_view_data("financial_year") :"" );
$financial_year_id = $beanUi->get_view_data("financial_year_id");
$financial_district_id = $beanUi->get_view_data("financial_district_id");
$allActivity = $beanUi->get_view_data("allActivity");
$allAnnualActivity = $beanUi->get_view_data("allAnnualActivity");
$array = $beanUi->get_view_data("array");
$sad = $beanUi->get_view_data("sad");
$hh = $beanUi->get_view_data("hh");
$ppedc = $beanUi->get_view_data("ppedc");
$ppedp = $beanUi->get_view_data("ppedp");
$ppegc = $beanUi->get_view_data("ppegc");
$ppegp = $beanUi->get_view_data("ppegp");
$trwsdist = $beanUi->get_view_data("trwsdist");
$trwsgen = $beanUi->get_view_data("trwsgen");
$trgenc = $beanUi->get_view_data("trgenc");

$controller->get_header();
$site_root_url = dirname(url());
$arr = array("#F2F2F2","#DDD9C3","#C6D9F1","#DCE6F2","#F2DCDB","#E6E0EC","#FDEADA","#FCD5B5");

?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
//jQuery(function($) {
//    $('.auto').autoNumeric('init');
//});
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">ANNUAL ACTIVITIES SCHEDULE OF SAFETY CELL <?php echo $financial_year; ?></h1> 
    <div id="show_message"><?php echo $beanUi->get_message();   ?></div>
    <hr />
    
    <form action="" method="post" id="fyr" enctype="multipart/form-data">
        
        <label class="col-sm-2">Financial Year</label>
        <div class="col-sm-4">
            
        <select name="financial_year" id="financial_yearselect" class="form-control">
            <option value="">select</option>
            <?php
            foreach ($allFinancialYear as $key => $value) {
                echo '<option value="'.$value->id.'" '.(($value->id == @$_REQUEST["financial_year"]) ? "selected" : "").' >'.$value->financial_year.'</option>';
            }
            ?>
        </select>
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="Final_TargetView.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> 
    <form action="" method="post" enctype="multipart/form-data">
    <?php if(!empty($getAllMonthofFinancialYear)) { ?>
        <table class="table table-bordered table-condensed table-responsive" style="background: #BDBDBD;">
        <thead>
        <tr>
            <th bgcolor="#B3A2C7">SL</th>
            <th  width="20%" style="vertical-align:middle;" bgcolor="#B3A2C7">ACTIVITIES</th>
            <th  width="10%" style="vertical-align:middle;" bgcolor="#B3A2C7">DIV</th>
            <th  width="10%" style="vertical-align:middle;" bgcolor="#B3A2C7">TARGET</th>
            <?php
            foreach($getAllMonthofFinancialYear as $rowdata)
            {
                echo '<th bgcolor="#D99694">'.date("M y",strtotime($rowdata)).'</th>';
            }
            ?>
             <th bgcolor="#D99694">TOTAL</th>
        </tr>
    </thead>
    <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
    <input type="hidden" name="financial_district_id" value="<?php echo @$financial_district_id; ?>" />
                <?php
                foreach ($allAnnualActivity as $key => $rowdata) {
                    echo '<tr class="totalCal" >' 
                            . '<td>'.($key+1).'</td>'                           
                            . '<td><b>' . $rowdata->activity_name . '</b></td>'
                            . '<td><b>' . $rowdata->dept_div . '</b></td>';
                            foreach ($array as $m => $val) {
                                if($m == $rowdata->id) {
                                    $total = 0;
                                    foreach ($getAllMonthofFinancialYear as $k => $v) {
                                        if($rowdata->id == 1) {
                                            foreach ($sad as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }

                                        } else if($rowdata->id == 2) {
                                            foreach ($hh as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }
                                        } else if($rowdata->id == 4) {
                                            foreach ($ppedc as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }
                                        } else if($rowdata->id == 5) {
                                            foreach ($ppedp as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }
                                        } else if($rowdata->id == 6) {
                                            foreach ($ppegc as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }
                                        } else if($rowdata->id == 7) {
                                            foreach ($ppegp as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }
                                        } else if($rowdata->id == 10) {
                                            foreach ($trgenc as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }
                                        } else if($rowdata->id == 11) {
                                            foreach ($trwsdist as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }
                                        } else if($rowdata->id == 12) {
                                            foreach ($trwsgen as $key => $value) {
                                                if($key == ($k + 1)) {
                                                    $tot += $value; 
                                                }
                                            }
                                        } else {
                                            @$total +=  $array[$m][$k+1];
                                        }
                                    }
                                    if($rowdata->id == 1 || $rowdata->id == 2 || $rowdata->id == 4 || $rowdata->id == 5 || $rowdata->id == 6 || $rowdata->id == 7 || $rowdata->id == 10 || $rowdata->id == 11 || $rowdata->id == 12 ) {
                                        echo '<td>'.$tot.'</td>';
                                        $tot = 0;
                                    } else {
                                        echo '<td>'.$total.'</td>';
                                    }
                                }
                            }
                    foreach ($array as $m => $val) {
                        if($m == $rowdata->id) {
                            $total = 0;
                            foreach ($getAllMonthofFinancialYear as $k => $v) {
                                if($rowdata->id == 1) {
                                    if($sad) {
                                        foreach ($sad as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            }
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else if($rowdata->id == 2) {
                                    if($hh) {
                                        foreach ($hh as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            } 
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else if($rowdata->id == 4) {
                                    if($ppedc) {
                                        foreach ($ppedc as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            }
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else if($rowdata->id == 5) {
                                    if($ppedp) {
                                        foreach ($ppedp as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            }
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else if($rowdata->id == 6) {
                                    if($ppegc) {
                                        foreach ($ppegc as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            }
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else if($rowdata->id == 7) {
                                    if($ppegp) {
                                        foreach ($ppegp as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            }
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else if($rowdata->id == 10) {
                                    if($trgenc) {
                                        foreach ($trgenc as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            }
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else if($rowdata->id == 11) {
                                    if($trwsdist) {
                                        foreach ($trwsdist as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            }
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else if($rowdata->id == 12) {
                                    if($trwsgen) {
                                        foreach ($trwsgen as $key => $value) {
                                            if($key == ($k + 1)) {
                                                echo '<td>'.$value.'</td>';
                                                $tot += $value; 
                                            }
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }
                                } else {
                                    echo '<td>'.$array[$m][$k+1].'</td>';
                                    @$total +=  $array[$m][$k+1];
                                }
                            }
                            if($rowdata->id == 1 || $rowdata->id == 2 || $rowdata->id == 4 || $rowdata->id == 5 || $rowdata->id == 6 || $rowdata->id == 7 || $rowdata->id == 10 || $rowdata->id == 11 || $rowdata->id == 12 ) {
                                echo '<td>'.$tot.'</td>';
                                $tot = 0;
                            } else {
                                echo '<td>'.$total.'</td>';
                            }
                        }
                    }
                    echo '</tr>';
                }                  
                ?>        
            </tbody>    
    </table>      
    <?php } ?>
    </form>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>