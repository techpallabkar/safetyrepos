<div class="clearfix"></div>
</div>
</div>
<div class="footer clearfix">
	<div class="pull-left">
	All right reserved &copy; CESC Safety Monitoring System, <?php echo date("Y");?>
	</div>
	<div class="pull-right">
		Designed & developed by <a href="http://viavitae.co.in" target="_blank">Via Vitae Solutions</a>
	</div>
	<div class="clearfix"></div>

<script type="text/javascript">
var root_url = "<?php echo url(); ?>";
var page_url = "<?php echo $_SERVER['PHP_SELF']; ?>";

$(document).ready(function () {
         $(".paginate_button").hide();
         $(".paginate_button:eq(2)").show();
         $(".paginate_button.current").show();
         $(".paginate_button.previous").show();
         $(".paginate_button").nextAll(".paginate_button:first").show();
         $(".paginate_button:nth-last-child(3)").show();
         $(".paginate_button:nth-last-child(2)").show();
         $(".paginate_button.current").nextAll(".paginate_button:lt(5)").show();
         $(".paginate_button.current").prevAll(".paginate_button:lt(3)").show();
         $(".paginate_button.current").nextAll(".paginate_button:gt(-2)").show(); 
        });

</script>

<script type="text/javascript" src="<?php echo url('assets/js/default.js') ?>"></script>

<?php
//echo $this->loadExtraJs();
//echo $this->getViewVars('datatableHTML');
//echo $this->getViewData('extrajs');

echo $this->loadJs();
?>
</div>

<div class="clearfix"></div>
