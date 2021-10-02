<?php
echo $this->Html->script('uploader/vendor/jquery.ui.widget');
echo $this->Html->script('uploader/jquery.iframe-transport');
echo $this->Html->script('uploader/jquery.fileupload');

?>
<style>    
    #grid .display tr td:nth-child(4){
        direction:ltr;
    }
</style>
<script type="text/javascript" >
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/Biography/', true) ?>';
        columns = [           
            { "data": "id", "visible" : false },
            { "data": "image", "width": "100px",sortable: false},  
            { "data": "name", "width": "200px"},
            { "data": "detail",sortable: false},                     
        ];        
        orders = [[2, "desc"]];
        $("#Form").submit(function(e) {
            e.preventDefault();            
            save(e,'save');           
        });                       
        
       	$(".SearchForm").submit(function(e) {
            e.preventDefault();            
            if (table) {
                table.api().ajax.reload(null, true);
            }
        });
       	$('.imageBox').on('click', 'img', function() {
            $(this).remove();
            $('#imageName').val('');
        });
    });
    function clearEntity(){    	
    	$('input, textarea').val('').attr('checked', false);
    	$('.imageBox img').click();            
    	btnStatus();
    	currentEntity = null;
    }
    function _editRecord(){
  	   var id = table.fnGetData($(this).index())['id'];    
     	loadById(id,null,null,setEntity);
 	}
     function editRecord(){
 		var id = gridRow('id');
     	if(!id.length) return;
     	loadById(id[0],null,null,setEntity);
 	}
 	function setEntity(e){
 		model = "Biography";
 		e = e.Biography;			
 		for(prop in e){                    
             var sel = '[name="data['+model+']['+prop+']"]';
             var val = e[prop];                        
             if(val) $(sel).val(val);            
             $('input[type=checkbox]' + sel).prop('checked', val == 1 ? true : false);
         }        
         setImage(e.image);
 	}
    function setImage(name){
		if(name){
			$img = $('<img />').attr('data-name', name).attr('src', '<?php echo $this->webroot; ?>img/bio/thumb/' + name);                
        	$('#images').html($img);
        	$('#imageName').val(name);
        }
	}
    $(function() {        
        $('#image').fileupload({
            dataType: 'json',
            add: function(e, data) {
                data.submit();
                $('button').attr('disabled', true);
                btnStatus();
            },
            done: function(e, data) {
            	setImage(data.result.name);                
                $('button').attr('disabled', false);
                btnStatus();
            },
            error: function(e) {
                $('button').attr('disabled', false);
                btnStatus();             
            }
        });               
    });
</script>
<div class="inProgress"></div>
<form role="form" style="direction:rtl;" id="Form">      
    <input id="id" name="data[Biography][id]" type="hidden" value="" >          
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
                <label class="message star">نام پزشک</label>    
                <div class="col-xs-8">     
                    <input type="text" name="data[Biography][name]" class="form-control" >          
                </div>
            </td>            
        </tr>    
        <tr>            
            <td>
                <label class="message star">بیوگرافی پزشک</label>    
                <div class="col-xs-8">     
                    <textarea name="data[Biography][detail]" rows="10" class="form-control" ></textarea>          
                </div>
            </td>            
        </tr>           
        <tr>            
            <td>
                <label class="message star">تصویر</label>    
                <div class="col-xs-8">     
                    <input id="image" name="image" class="form-control" type="file" data-url="<?php echo Router::url('/Biography/', true) ?>uploadImage" >
                    <input type="hidden" name="data[Biography][image]" id="imageName" >                    
                	<div id="images" class="imageBox" ></div>          
                </div>                
            </td>            
        </tr>     
        <tr>            
            <td class="btnNav">           
            	<button type="button" disabled="disabled" onclick="removeEntity()" class="btn btn-danger delete">حذف</button>     
                <button type="button" onclick="clearEntity()" class="btn btn-info new">جدید</button>
                <button type="button" onclick="fillTable('view',orders)" class="btn btn-default view">نمایش</button>
                <button type="submit" class="btn btn-primary save">ثبت</button>                      
            </td>
        </tr>
    </table>       
</form>
<div id="grid" style="display:none">   
    <form class="SearchForm" id="SearchForm">
        <table class="display" style="width:750px" >            
            <tr>
            	<td>
                    <label>نام پزشک</label>    
                    <div class="col-xs-9">     
                        <input name="name" type="text" class="form-control" >          
                    </div>
                </td>
                <td class="btnNav">
                	<button type="button" onclick="clearFilter();" class="btn btn-default filter">حذف فیلتر</button>
                    <button type="submit" class="btn btn-primary search">جستجو</button>                      
                <td>
            </tr>                                  
        </table>
    </form>
    <?php echo $this->element('gridTools'); ?>
    <table class="display" style="width:99%" >
        <thead>
            <tr>
                <th>شماره</th>
                <th>تصویر پزشک</th>
                <th>نام پزشک</th>
                <th>بیوگرافی پزشک</th> 
            </tr>
        </thead>        
    </table>
    <?php echo $this->element('gridTools'); ?>
</div>