<?php
echo $this->Html->script('selectize.min');
echo $this->Html->css('selectize.default');
?>
<script type="text/javascript" >
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/contact_us/', true) ?>';        
        $("#Form").submit(function(e) {
            e.preventDefault();            
            save(e,'save');           
        });               
        
        var $selectize = $('#tels').selectize({
            plugins: ['remove_button','restore_on_backspace'],
            delimiter: ';%&',            
            persist: false,
            createOnBlur: true,
            create: true
        });
        tels = $selectize[0].selectize;
        
        loadById(-1);
    }); 
    function showLatoLon(){	
    	var lat = $('#latitude').val();
    	var lon = $('#longitude').val();    	
   		   		
    	var rest = '<?php echo Router::url('/Api/', false) ?>';
    	showModalForm(rest+"location?lat="+lat+"&lon="+lon,function(e){
			$('#latitude').val(e.lat);
			$('#longitude').val(e.lon);
		});
    }
    function aClearEntity(){
    	tels.clearOptions();
    	clearEntity();
    }
    function loadById(id) {	
    	aClearEntity();				
		$.ajax({
			url : restUrl + 'load/' + id,
			dataType : "json",
			beforeSend : function() {
				progress(true);
			},
			success : function(entity) {
				currentEntity = entity;
				for (model in entity) {
					if(model == 'ContactUs'){
						for (prop in entity[model]) {
							var sel = '[name="data[' + model + '][' + prop + ']"]';
							var val = entity[model][prop];
							if (val)
								$('input[type!=checkbox]'+sel+',select'+sel+',textarea'+sel).val(val).change();
							if (entity[model].length) {
								var s = '[name="data[' + model
										+ '][id][]"] option[value='
										+ entity[model][prop].id + ']';
								$('select' + s).attr('selected', 'selected');
							}
							$('input[type=checkbox]' + sel).prop('checked',
									val == 1 ? true : false);
						}						
					} else if (model == 'Tels'){
						$(entity[model]).each(function(i, e){							
							tels.addOption({
					            text: e.telephone,
					            value: e.telephone
					        });						
				        	tels.addItem(e.telephone);						
						});
					}
				}				
				btnStatus();
			},
			complete : function() {
				progress(false);
			}
		});
	}   
</script>
<div class="inProgress"></div>
<form role="form" style="direction:rtl;" id="Form">      
    <input id="id" name="data[ContactUs][id]" type="hidden" value="" >          
    <table style="width:700px">
    	<tr>            
            <td>
                <label class="message">درمانگاه</label>    
                <div class="col-xs-8">     
                	<?php echo $clinic; ?>      
                </div>
            </td>            
        </tr>
        <tr> 
            <td>
                <label class="message">آدرس</label>    
                <div class="col-xs-8">
               		<textarea name="data[ContactUs][address]" class="form-control" rows="4" ></textarea>
                </div>
            </td>            
        </tr>
        <tr>            
            <td>
                <label class="message">کد پستی</label>    
                <div class="col-xs-8">     
                	<input class="form-control ltr" name="data[ContactUs][postal_code]" maxlength=10 >      
                </div>
            </td>            
        </tr>        
        <tr>            
            <td>
                <label class="message">پست الکترونیک</label>    
                <div class="col-xs-8">     
                	<input class="form-control ltr" name="data[ContactUs][email]" >      
                </div>
            </td>            
        </tr>
        <tr>
        	<td>                   
        		<label class="message">طول جغرافیایی</label>
        		<div class="col-xs-8">
                	<input name="data[ContactUs][longitude]" type="text" class="form-control ltr" id="longitude" >
                	<?php
                		echo $this->Html->image('DESIGN-38.png', array('onclick'=>'showLatoLon()','class'=>'lov'));
                	?>                   	                	                                             	            		     
                </div>
            </td>
        </tr>
        <tr>
        	<td>                   
        		<label class="message">عرض جغرافیایی</label>
        		<div class="col-xs-8">
                	<input name="data[ContactUs][latitude]" type="text" class="form-control ltr" id="latitude" >
                	<?php
                		echo $this->Html->image('DESIGN-38.png', array('onclick'=>'showLatoLon()','class'=>'lov'));
                	?>          
                </div>
            </td>
        </tr>
        <tr>            
            <td>
                <label class="message star">شماره تلفن ها</label>    
                <div class="col-xs-8">     
                    <input class="ltr" id="tels" name="data[ContactUs][telephone]" readonly="readonly">          
                </div>
            </td>            
        </tr>                
        <tr>            
            <td class="btnNav">                                                     
                <button type="submit" class="btn btn-primary save">ویرایش</button>                      
            </td>
        </tr>
    </table>       
</form>