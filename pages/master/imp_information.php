<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("CmsController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;

$importantinformation = $beanUi->get_view_data("importantinformation");
$presCtr->get_header();
?>
<div class="mh-wrapper mh-home clearfix">
     <div class="print1">
        <input  type="button" value="print" onclick="PrintDiv();" />
   </div>
    <div class="clearfix"></div>
    <div class="print-friendly" id="divToPrint">
        <header class="mh-header">
                <div class="mh-container mh-container-inner mh-row clearfix">
                    <div class="mh-custom-header" role="banner">
                       
                        <div class="mh-site-logo" style="float: left;">
                                <img class="mh-header-image" src="<?php echo url("assets/images/cesc-logo.png"); ?>" alt="CESC Safety Monitoring System logo" />
                                <div style="font-size:11px; ">Safety Monitoring System</div>
                            </div>
                        <div class="header-right" style="float: right;">
                            <img class="mh-header-image" src="<?php echo url("assets/images/rpgs-logo.png"); ?>" alt="RPSG" />
                        </div>
                    </div>
                </div>
            <div style="clear: both;"></div>
            </header>	
        <div class="clearfix"></div> 
    <div class="topic-heading clearfix">
        <h3>Important Information</h3>
    </div>
        <div>
           <table class="table table-bordered table-condensed table-responsive">
              <thead>
                
            </thead>
            <tbody>
                <tr>
                    <?php
                    echo @$importantinformation[0]->content;
                    ?>
                </tr>
            </tbody>
            </table>
        </div>
	</div>
</div>	
<?php $presCtr->get_footer(); ?>
</body>
</html>


<script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=800,height=300');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>