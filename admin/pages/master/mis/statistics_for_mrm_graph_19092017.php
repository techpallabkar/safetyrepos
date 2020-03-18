<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$allDepertment = $beanUi->get_view_data("allDepertment");
$get_statistics_data = $beanUi->get_view_data("set_statistics_data");
$get_rerurn_data = $beanUi->get_view_data("set_rerurn_data");
        
$controller->get_header();
$site_root_url = dirname(url());
?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<style>
    #chart {
        width: 827px;
        /*height: 700px;*/
        margin: 30px auto 0;
        /*overflow: scroll;*/
        display: block;
        float: right;
        margin-right: 36px;
    }

    #chart #numbers {
        height: 100%;
        width: 50px;
        margin: 0;
        padding: 0;
        display: inline-block;
        float: left;
    }

    #chart #numbers li {
        text-align: right;
        padding-right: 1em;
        list-style: none;
        height: 29px;
        border-bottom: 1px solid #0000CD;
        position: relative;
        bottom: 30px;
    }

    #chart #numbers li:last-child { height: 30px; }

    #chart #numbers li span {
        color: #0F2A4A;
        bottom: 0;
        right: 10px;
    }

    #chart #bars {
        display: inline-block;
        background: rgba(0, 0, 0, 0.2);
        background: url("<?php echo url("assets/images/graph4.jpg"); ?>");
        padding: 0;
        margin: 0;
    }

    #chart #bars li {
        display: table-cell;
        width: 100px;
        height: 300px;
        margin: 0;
        text-align: center;
        position: relative;
    }

    #chart #bars li .bar {
        display: block;
        width: 20px;
        margin-left: 15px;
        background: #001D3F;
        position: absolute;
        bottom: 0;
        color: #fff;
    }
    
    #chart #bars li .bar .ptext {
        transform: rotate(-90deg);
        color: #fff;
        margin-bottom: 100px;
        margin-left: 8px;
        font-weight: bold; 
    }
    
    #chart #bars li .bar:hover {
        background: #20B2AA;
        cursor: pointer;
    }

    #chart #bars li span {
        color:blue;
        width: 30%;
        position: absolute;
        bottom: -2em;
        left: 0;
        text-align: center;
    }

</style>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">STATISTICS FOR MRM</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>

    <form action="" method="post" id="fyr" enctype="multipart/form-data">


        <table class="table">
            <tr>
                <td style="width:15%; vertical-align: middle;">Financial Month-Year <span style="float: right; vertical-align: middle">:</span></td>
                <td style="width:20%; vertical-align: middle;"><input type="text" name="month_year_from" id="month_year_from" class="month_year_picker form-control" value = "<?php echo $get_rerurn_data['date']; ?>" style="width: 100%; margin-bottom: 0;" required></td>
                <td style="width:15%; vertical-align: middle;">Select Department <span style="float: right; vertical-align: middle">:</span></td>
                <td style="width:20%; vertical-align: middle;">
                    <select id="tree_id" style=" margin-bottom: 0;" name="tree_id" class="form-control" required>
                        <option value="">--SELECT--</option>
                        <?php
                        foreach ($allDepertment as $key => $value) {
                            echo '<option value="' . $value["tree_id"] . '"'.(($value["tree_id"] == $get_rerurn_data["tree_id"]) ? "selected" : "").'>' . $value["dept_name"] . '</option>';
                        }
                        ?>
                    </select>
                </td>
                <td style="width:30%; vertical-align: middle;">
                    
                    <input type="hidden" name="_action" value="Create" />
                    <button type="submit" name="B1" class="btn btn-primary btn-sm"> Go </button>
                </td>
            </tr>
        </table>
    </form>

    <hr/> 


    <?php if (!empty($get_statistics_data)) { ?>
        <!--<button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
            Export to Excel</button>-->
       <!--<button type="button" id="ExportToPdf" class="btn btn-danger" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print PDF </button>-->
     <div class="print1">
        <input class="btn btn-primary" type="button" value="Print / PDF" onclick="PrintDiv();" />
   </div>
    <div class="print-friendly" id="divToPrint">
    <!--<div id='TBL_EXPORT'>-->
            <table class="table table-bordered table-condensed table-responsive" style="font-size:13px; width: 100%;" >
                <thead class="bg-primary">    
                    <tr>
                        <th>DEPT / SECTN</th>
                        <?php
                        foreach ($get_statistics_data["PREV_YEAR"] as $key => $value) {
                            $arr = explode(" AND ", $str = str_replace("'", "", $key));
                            echo '<th>' . (date("Y", strtotime($arr[0]))) . "-" . (date("y", strtotime($arr[1]))) . '</th>';
                        }

                        echo '<th></th>';
                        foreach ($get_statistics_data["CUR_YEAR"] as $key1 => $value1) {
                            echo '<th>' . (date("M-y", strtotime($key1))) . '</th>';
                        }

                        foreach ($get_statistics_data["CUR_YEAR_YTM"] as $key2 => $value2) {
                            $arr2 = explode(" AND ", $str2 = str_replace("'", "", $key2));
                            echo '<th>YTM[' . (date("Y", strtotime($arr2[0]))) . "-" . (date("y", strtotime($arr2[1]))) . ']</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <tr style='text-align: center;'>
                        <td><span id="dist"></span></td>
                        <?php
                        foreach ($get_statistics_data["PREV_YEAR"] as $key => $value) {
                            echo '<td>' . ($value) . '</td>';
                        }

                        echo '<td></td>';
                        foreach ($get_statistics_data["CUR_YEAR"] as $key1 => $value1) {
                            echo '<td>' . ($value1) . '</td>';
                        }

                        foreach ($get_statistics_data["CUR_YEAR_YTM"] as $key2 => $value2) {
                            echo '<td>' . ($value2) . '</td>';
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
       
        <div id="chart" >
            <ul id="bars">
                <?php
                foreach ($get_statistics_data["PREV_YEAR"] as $key => $value) {
                    $arr = explode(" AND ", $str = str_replace("'", "", $key));
                    echo '<li><div data-percentage="' . ($value) . '" class="bar"><span class="ptext">' . ($value) . '</span></div><span>' . (date("Y", strtotime($arr[0]))) . "-" . (date("y", strtotime($arr[1]))) . '</span></li>';
                }

                echo '<th></th>';

                foreach ($get_statistics_data["CUR_YEAR"] as $key1 => $value1) {
                    echo '<li><div data-percentage="' . ($value1) . '" class="bar"><span class="ptext">' . ($value1) . '</span></div><span>' . (date("M-y", strtotime($key1))) . '</span></li>';
                }

                foreach ($get_statistics_data["CUR_YEAR_YTM"] as $key2 => $value2) {
                    $arr2 = explode(" AND ", $str2 = str_replace("'", "", $key2));
                    echo '<li><div data-percentage="' . ($value2) . '" class="bar"><span class="ptext">' . ($value2) . '</span></div><span>YTM(' . (date("Y", strtotime($arr2[0]))) . "-" . (date("y", strtotime($arr2[1]))) . ')</span></li>';
                }
                ?>
            </ul>
        </div>
    <!--</div>-->
    </div>
     <?php } ?>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>
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
    
    $(function () {
        $("#bars li .bar").each(function (key, bar) {
            var percentage = $(this).data('percentage');

            $(this).animate({
                'height': percentage + '%'
            }, 1000);
        })
    });
    
    $(document).ready(function () {
        $("#dist").html($('#tree_id :selected').text());
        
        /* Export to excel
        $("#ExportExcel").click(function (e) {
            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html());
            window.open(path);
            e.preventDefault();
        });
        */
       
    });

</script>
<script type="text/javascript">   
    function PrintDiv(){
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title>STATISTICS FOR MRM</title>');
    mywindow.document.write('<style>#chart{display: block;margin: 30px auto 0;border:1px solid #666;height:460px;font-size:12px !important;font-family:Arial !important;}#bars {margin-top:60px;}#chart #bars li {display: table-cell;height: 300px;margin: 0;position: relative;text-align: center;width: 100px;bottom:0 !important;}#chart #bars li span{position:absolute; bottom:0;left:7px;}#chart #bars{height:350px !important;display:block;width:1000px !important;}#chart #bars li .bar{background: #001d3f !important; bottom: 40px;color: #ffffff !important;display: block;margin-left: 15px;position: absolute;width: 20px;-webkit-print-color-adjust: exact;color-adjust: exact;}span.ptext {color:#fff !important;-webkit-print-color-adjust: exact;color-adjust: exact;font-weight: bold;margin-bottom: -18px;margin-left: -5px;left:0 !important}table{border-collapse:collapse;margin-bottom:15px;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}</style></head><body>');
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