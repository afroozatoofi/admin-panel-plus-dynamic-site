<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<style type="text/css">
	.gllpMap{width:100% !important; height:500px !important}
	body{padding:0 !important}	
</style>
<?php
echo $this->Html->script('jquery-gmaps-latlon-picker');
echo $this->Html->css('jquery-gmaps-latlon-picker');

// IRAN
$lat = 32.39851580247402;
$lon = 53.525390625;

// Shiraz
$lat = 29.611670115197377;
$lon = 52.535247802734375;
$zoom = 10;
if(!empty($this->request->query['lat']) && !empty($this->request->query['lon']) ){
	$lat = $this->request->query['lat'];
	$lon = $this->request->query['lon'];
	$zoom = 16;
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.gllpMap').on('dblclick',function(){			
			parent.$_returnvalue = {
				lat: $(".gllpLatitude").val(),
				lon: $(".gllpLongitude").val()
			};
			parent.$('#lov').modal('hide');
		})
	});
</script>
<fieldset class="gllpLatlonPicker" style="direction:rtl">
	<div style="text-align:right;">	
		<div class="col-xs-8" style="margin:10px 0">
			<input type="button" class="gllpSearchButton btn btn-primary" value="جستجو">
		</div>	
		<div class="col-xs-4" style="margin:10px 0">                      
	        <input type="text" class="gllpSearchField form-control">        
	    </div>		    
	 </div>
	<div class="gllpMap">Google Maps</div>
	<input type="hidden" class="gllpLatitude" value="<?php echo $lat ?>" />
	<input type="hidden" class="gllpLongitude" value="<?php echo $lon ?>" />
	<input type="hidden" class="gllpZoom" value="<?php echo $zoom ?>" />
 </fieldset>