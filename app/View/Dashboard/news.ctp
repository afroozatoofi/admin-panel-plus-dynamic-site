<?php
echo $this->Html->script('uploader/vendor/jquery.ui.widget');
echo $this->Html->script('uploader/jquery.iframe-transport');
echo $this->Html->script('uploader/jquery.fileupload');

echo $this->Html->script('calendar/calendar');
echo $this->Html->script('calendar/calendar-setup');
echo $this->Html->script('calendar/jalali');
echo $this->Html->script('calendar/lang/calendar-fa');
echo $this->Html->css('calendar/skins/calendar-blue');
?>
<style>    
    #grid .display tr td:nth-child(4){
        direction:ltr;
    }
</style>
<script type="text/javascript" >
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/news/', true) ?>';
        columns = [           
            { "data": "id", "visible" : false },
            { "data": "image", "width": "100px",sortable: false},  
            { "data": "title", "width": "100px"},
            { "data": "summary"},                     
            { "data": "jalaliDate", "width": "120px"}            
        ];        
        orders = [[4, "desc"]];
        $("#Form").submit(function(e) {
            e.preventDefault();            
            save(e,'save');           
        });                       
        
        $('.shdate').each(function(i,dd){       		       	
	       	Calendar.setup({
	            inputField: dd,
	            ifFormat: "%Y/%m/%d",
	            dateType: 'jalali',
	            showsTime: false
	        });
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
        Calendar.setup({
            inputField: "jalaliDate",
            ifFormat: "%Y/%m/%d %H:%M",
            dateType: 'jalali',
            weekNumbers: true,
            timeFormat: "24",
            showsTime: true
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
 		model = "News";
 		e = e.News;			
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
			$img = $('<img />').attr('data-name', name).attr('src', '<?php echo $this->webroot; ?>img/news/thumb/' + name);                
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
    <input id="id" name="data[News][id]" type="hidden" value="" >          
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
                <label class="message star">عنوان خبر</label>    
                <div class="col-xs-8">     
                    <input type="text" name="data[News][title]" class="form-control" >          
                </div>
            </td>            
        </tr>    
        <tr>            
            <td>
                <label class="message">تاریخ</label>    
                <div class="col-xs-8">     
               		<input name="data[News][jalaliDate]" readonly="readonly" type="text" class="form-control ltr" id="jalaliDate" >
                </div>
            </td>            
        </tr>        
        <tr>            
            <td>
                <label class="message star">خلاصه خبر</label>    
                <div class="col-xs-8">     
                    <textarea name="data[News][summary]" rows="4" class="form-control" ></textarea>          
                </div>
            </td>            
        </tr>    
        <tr>            
            <td>
                <label class="message star">متن خبر</label>    
                <div class="col-xs-8">     
                    <textarea name="data[News][text]" rows="10" class="form-control" ></textarea>          
                </div>
            </td>            
        </tr>           
        <tr>            
            <td>
                <label class="message star">تصویر</label>    
                <div class="col-xs-8">     
                    <input id="image" name="image" class="form-control" type="file" data-url="<?php echo Router::url('/News/', true) ?>uploadImage" >
                    <input type="hidden" name="data[News][image]" id="imageName" >                    
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
	                <label>از تاریخ</label>    
	                <div class="col-xs-9">     
	                    <input name="jalaliDate>=" readonly="readonly" type="text" class="form-control ltr shdate" >          
	                </div>
	            </td>
	            <td>
	                <label>تا تاریخ</label>    
	                <div class="col-xs-9">     
	                    <input name="jalaliDate<=" readonly="readonly" type="text" class="form-control ltr shdate" >          
	                </div>
	            </td>   
	            <td></td>                                                                           
            </tr>    
            <tr>
            	<td>
                    <label>عنوان خبر</label>    
                    <div class="col-xs-9">     
                        <input name="title" type="text" class="form-control" >          
                    </div>
                </td>
                <td>
                    <label>خلاصه خبر</label>    
                    <div class="col-xs-9">     
                        <input name="summary" type="text" class="form-control" >          
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
                <th>تصویر خبر</th>
                <th>عنوان خبر</th>
                <th>خلاصه خبر</th> 
                <th>تاریخ</th>
            </tr>
        </thead>        
    </table>
    <?php echo $this->element('gridTools'); ?>
</div>