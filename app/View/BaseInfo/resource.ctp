<style>
    #grid tr td:nth-child(2),#grid tr td:nth-child(3),#grid tr td:nth-child(4){
        text-align:left;
    }
</style>
<script type="text/javascript" >
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/Resources/', true) ?>';
        columns = [           
            { "data": "id", "visible" : false },
            { "data": "name"},
            { "data": "url"},
            { "data": "englishName"},
            { "data": "class"}
        ];

        $("#Form").submit(function(e) {
            e.preventDefault();            
            save(e,'save');                       
        }); 
        getAllCombo();
        $(".SearchForm").submit(function(e) {
            e.preventDefault();            
            if (table) {
                table.api().ajax.reload(null, true);
            }
        });
        getMaxCode();                             
        $('#type').change(function(){
        	if($(this).val() == 1){
        		$('.empty').removeClass('star');
        	} else {
        		$('.empty').addClass('star');
        	}
        });
    });
    function getMaxCode(){
    	$.getJSON(restUrl+'maxCode',function(code){
    		$('#code').val(code);
    	});
    }
    function getAllCombo(){
        fillCombo('#parent_id',restUrl+'getAll',null,'id','name','....');
    }
</script>
<div class="inProgress"></div>
<form role="form" style="direction:rtl;" id="Form">      
    <input id="id" name="data[Resource][id]" type="hidden" value="" >          
    <table style="width:700px">
        <tr>            
            <td>
                <label class="message star">نام منبع</label>    
                <div class="col-xs-8">     
                    <input name="data[Resource][name]" type="text" class="form-control" >          
                </div>
            </td>                        
        </tr>                
        <tr>            
            <td>
                <label class="message star empty">آدرس</label>    
                <div class="col-xs-8">     
                    <input name="data[Resource][url]" type="text" class="form-control ltr" >          
                </div>
            </td>                        
        </tr>                
        <tr>            
            <td>
                <label class="message star empty">نام انگلیسی</label>    
                <div class="col-xs-8">     
                    <input name="data[Resource][englishName]" type="text" class="form-control ltr" >          
                </div>
            </td>                        
        </tr>                
        <tr>            
            <td>
                <label class="message star">کد منبع</label>    
                <div class="col-xs-8">
                    <input name="data[Resource][code]" id="code" type="text" class="form-control ltr noedit" >          
                </div>
            </td>                        
        </tr>                
        <tr>            
            <td>
                <label class="message star">نوع</label>    
                <div class="col-xs-8">                         
                    <select name="data[Resource][type]" id="type" class="form-control">                        
                        <option value="1" >دسته بندی</option>
                        <option value="2" selected="selected" >منو</option>
                    </select>
                </div>
            </td>                        
        </tr>                
        <tr>            
            <td>
                <label class="message">کلاس</label>    
                <div class="col-xs-8">     
                    <input name="data[Resource][class]" type="text" class="form-control ltr" >          
                </div>
            </td>                        
        </tr>                
        <tr>            
            <td>
                <label class="message star empty">دسته بندی</label>    
                <div class="col-xs-8">                    
                    <select name="data[Resource][parent_id]" id="parent_id" class="form-control"></select>
                </div>
            </td>                        
        </tr>                
        <tr>            
            <td>
                <label class="message">ترتیب</label>    
                <div class="col-xs-8">                    
                    <input name="data[Resource][index]" class="form-control ltr" type='number'>
                </div>
            </td>                        
        </tr>                
        <tr>            
            <td class="btnNav">                                
                <button type="button" disabled="disabled" onclick="removeEntity()" class="btn btn-danger delete">حذف</button>                
                <button type="button" onclick="clearEntity(true);getMaxCode();" class="btn btn-info new">جدید</button>                
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
                    <label class="message">نام منبع</label>    
                    <div class="col-xs-8">     
                        <input name="name" type="text" class="form-control" >          
                    </div>
                </td>
                <td>
                    <label class="message">نام انگلیسی</label>    
                    <div class="col-xs-8">     
                        <input name="englishName" type="text" class="form-control ltr" >          
                    </div>
                </td>                        
            </tr>                
            <tr>
                <td>
                    <label class="message">آدرس</label>    
                    <div class="col-xs-8">     
                        <input name="url" type="text" class="form-control ltr" >          
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
                <th>نام منبع</th>                
                <th>آدرس</th>
                <th>نام انگلیسی</th>
                <th>کلاس</th>
            </tr>
        </thead>        
    </table>
    <?php echo $this->element('gridTools'); ?>
</div>