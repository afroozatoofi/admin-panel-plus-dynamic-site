<script type="text/javascript" >
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/Groups/', true) ?>';
        columns = [           
            { "data": "id", "visible" : false },
            { "data": "name"}
        ];

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
    <input id="id" name="data[Group][id]" type="hidden" value="" >          
    <table style="width:700px">
        <tr>            
            <td>
                <label class="message star">نام گروه کاربری</label>    
                <div class="col-xs-8">     
                    <input name="data[Group][name]" type="text" class="form-control" >          
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
                    <label class="message">نام گروه</label>    
                    <div class="col-xs-8">     
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
                <th>نام گروه کاربری</th>                
            </tr>
        </thead>        
    </table>
    <?php echo $this->element('gridTools'); ?>
</div>