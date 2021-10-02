<script type="text/javascript" >
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/section/', true) ?>';
        columns = [           
            { "data": "id", "visible" : false },
            { "data": "index", "width": 50},  
            { "data": "title", "width": 200},
            { "data": "description",sortable: false}                                          
        ];        
        orders = [[1, "asc"]];
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
    });     	    
</script>
<div class="inProgress"></div>
<form role="form" style="direction:rtl;" id="Form">      
    <input id="id" name="data[Section][id]" type="hidden" value="" >          
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
                <label class="message star">عنوان بخش</label>    
                <div class="col-xs-8">     
                    <input type="text" name="data[Section][title]" class="form-control" >          
                </div>
            </td>            
        </tr>    
        <tr>            
            <td>
                <label class="message">ترتیب نمایش</label>    
                <div class="col-xs-8">     
               		<input type="text" name="data[Section][index]" class="form-control" >
                </div>
            </td>            
        </tr>        
        <tr>            
            <td>
                <label class="message star">توضیحات بخش</label>    
                <div class="col-xs-8">     
                    <textarea name="data[Section][description]" rows="6" class="form-control" ></textarea>          
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
                    <label>عنوان بخش</label>    
                    <div class="col-xs-9">     
                        <input name="title" type="text" class="form-control" >          
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
    <table class="display" style="width:99%">
        <thead>
            <tr>
                <th>شماره</th>                
                <th>ترتیب</th> 
                <th>عنوان بخش</th>
                <th>توضیحات بخش</th>
            </tr>
        </thead>        
    </table>
    <?php echo $this->element('gridTools'); ?>
</div>