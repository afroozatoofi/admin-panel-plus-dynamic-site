<?php
echo $this->Html->script('uploader/vendor/jquery.ui.widget');
echo $this->Html->script('uploader/jquery.iframe-transport');
echo $this->Html->script('uploader/jquery.fileupload');
?>
<style type="text/css">
	.imageBox img{
		width: 445px;
	}
</style>
<script type="text/javascript" >
	var lov  = '<?php if(empty($this->request->query['lov'])) echo '0'; else echo '1'; ?>';
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/Slider/', true) ?>';
        columns = [           
            { "data": "id", "visible" : false },
            { "data": "image","width": "100px",sortable: false},            
            { "data": "title"},            
            { "data": "isEnabled","width": "80px"}
        ];
        
        if(lov != '0'){
        	fillTable('view', undefined, true);
        	$('#grid').on('dblclick', 'tr', function (e,i) {        		
        		parent.$_returnvalue = {
        			id : table.fnGetData(this)['id'],
        			name : table.fnGetData(this)['name']
        		};
        		parent.$('#lov').modal('hide');            	           
        	}); 
        } else {
        	showEdit();
        }   

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
        });
    });
    function clearEntity(){    	
    	$('input, textarea').val('').attr('checked', false);
    	$('.imageBox img').click();            
    	btnStatus();
    	currentEntity = null;
    	$('#isEnabled').prop('checked',true);
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
		model = "Slider";
		e = e.Slider;			
		for(prop in e){                    
            var sel = '[name="data['+model+']['+prop+']"]';
            var val = e[prop];                        
            if(val) $(sel).val(val);            
            $('input[type=checkbox]' + sel).prop('checked', val == 1 ? true : false);
        }        
        setImage(e.image);
	}
    function save(e, url) {
		var image = $('#images img').data('name');
		e = $(e.target).serialize()+"&data[Slider][image]="+(image ? image : "");		
	    $.ajax({
	        url: restUrl + url,
	        type: "post",
	        dataType: "json",        
	        data: e,
	        beforeSend: function() {
	            progress(true);
	        },
	        success: function(id) {	        	
	        	$('#id').val(id);
	        	btnStatus();            
	        },       
	        complete: function() {
	            progress(false);
	        }
	    });
	}
    function setImage(name){
		if(name){
			$img = $('<img />').attr('data-name', name).attr('src', '<?php echo $this->webroot; ?>img/slider/thumb/' + name);                
        	$('#images').html($img);
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
    <input id="id" name="data[Slider][id]" type="hidden" value="" >          
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
                <label class="message">عنوان</label>    
                <div class="col-xs-8">     
                    <textarea name="data[Slider][title]" rows="3" class="form-control" ></textarea>          
                </div>
            </td>                              
        </tr>
        <tr>            
            <td>
                <label class="message star">تصویر</label>    
                <div class="col-xs-8">     
                    <input id="image" name="image" class="form-control" type="file" data-url="<?php echo Router::url('/Slider/', true) ?>uploadImage" >                    
                	<div id="images" class="imageBox" ></div>          
                </div>                
            </td>            
        </tr>
        <tr>
        	<td>
                <label class="message" for="isEnabled">نمایش</label>    
                <div class="col-xs-8" style="text-align:right">                         
                    <div class="checkbox">
                        <label>
                            <input id="isEnabled" checked="checked" name="data[Slider][isEnabled]" type="checkbox" value="1" >
                        </label>
                    </div>
                </div>
            </td>     
        </tr>       	        
        <tr>            
            <td class="btnNav">           
            	<button type="button" disabled="disabled" onclick="removeEntity()" class="btn btn-danger delete">حذف</button>     
                <button type="button" onclick="clearEntity()" class="btn btn-info new">جدید</button>
                <button type="button" onclick="fillTable('view')" class="btn btn-default view">نمایش</button>
                <button type="submit" class="btn btn-primary save">ثبت</button>                      
            </td>
        </tr>
    </table>       
</form>
<div id="grid" style="display:none">   
    <form class="SearchForm" id="SearchForm">
        <table class="display" style="width:700px" >            
            <tr>
            	<td>
                    <label class="message">عنوان</label>    
                    <div class="col-xs-8">     
                        <input name="title" type="text" class="form-control" >          
                    </div>
                </td>
            	<td>
                	<label for="isEnabled2">نمایش</label>    
	                <div class="col-xs-2">
	                	<input id="isEnabled2" name="isEnabled" type="checkbox" value="1" >	                        
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
                <th>تصویر</th>
                <th>عنوان</th>                                                                                                
                <th>نمایش</th>           
            </tr>
        </thead>        
    </table>
    <?php echo $this->element('gridTools'); ?>
</div>