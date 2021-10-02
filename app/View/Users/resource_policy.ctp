<?php
echo $this->Html->script('selectize.min');
echo $this->Html->css('selectize.default');
?>
<script type="text/javascript" >
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/ResourcePolicies/', true) ?>';      
        $("#Form").submit(function(e) {
            e.preventDefault();            
            save(e,'save');           
        });
        $('#groupId').change(function(){                        
            aClearEntity(false);
            $('.btnNav button').attr('disabled', true);
            $.getJSON(restUrl+'getAll/'+this.value,function(rs){            
                $('.btnNav button').attr('disabled', false);                
                $(rs).each(function(i,r){                                                           
					resources.addItem(r.resource_id);	
                });                
            });
        });                
		resources = null;
        fillCombo('#groupId',restUrl+'getAllGroup',null,'id','name','....');        
        fillCombo('#resourceId',restUrl+'getAllMenu',null,'id','name',null,function(){
        	if(resources === null){
        		$selectize = $('#resourceId').removeClass('form-control').selectize();        	
        		resources = $selectize[0].selectize;	                	        
        	}
        });                      
    });
    function aClearEntity(flag) {
    	if(flag){
    		$('#groupId option').removeAttr('selected');
    	}
    	$('#resourceId option').removeAttr('selected');
    	if(resources !== null){
        	resources.clear();
		}    	     
    }
</script>
<div class="inProgress"></div>
<form role="form" style="direction:rtl;" id="Form">          
    <table style="width:700px">
        <tr>            
            <td>
                <label class="message star">گروه کاربری</label>    
                <div class="col-xs-8">                         
                    <select name="data[ResourcePolicy][group_id]" id="groupId" class="form-control">                    	                      
                    </select>
                </div>
            </td>            
        </tr>                
        <tr>            
            <td>
                <label class="message">منابع</label>    
                <div class="col-xs-8">                         
                    <select multiple="multiple" style="height:29px" name="data[ResourcePolicy][resource_id][]" id="resourceId" class="form-control">
                    </select>
                </div>
            </td>            
        </tr>                
        <tr>            
            <td class="btnNav">
                <button type="button" onclick="aClearEntity(true)" class="btn btn-info new">جدید</button>                                
                <button type="submit" class="btn btn-primary save">ثبت</button>                      
            </td>
        </tr>
    </table>       
</form>