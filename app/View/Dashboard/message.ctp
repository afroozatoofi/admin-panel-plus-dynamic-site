<?php
echo $this->Html->script('selectize.min');
echo $this->Html->css('selectize.default');

echo $this->Html->script('calendar/calendar');
echo $this->Html->script('calendar/calendar-setup');
echo $this->Html->script('calendar/jalali');
echo $this->Html->script('calendar/lang/calendar-fa');
echo $this->Html->css('calendar/skins/calendar-blue');
?>
<style type="text/css">
	#grid .display tr td:nth-child(3){        
        direction: ltr;
    }
</style>
<script type="text/javascript" >
	
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/Messages/', true) ?>';
        columns = [           
            { "data": "id", "visible" : false },  
            { "data": "text",sortable: false},            
            { "data": "receivers",sortable: false,"width": "200px"},
            { "data": "jalaliDate", "width": "150px"}            
        ];
        orders = [[3, "desc"]];
        
        fillCombo('#clinicId',restUrl+'getAllClinics',null,'id','name',null,function(){        	
        	$selectize = $('#clinicId').removeClass('form-control').selectize({
        		plugins: ['remove_button']        		           
        	});        	
        	clinics = $selectize[0].selectize;	                	                	
        });
        
        $('.shdate').each(function(i,dd){       		       	
	       	Calendar.setup({
	            inputField: dd,
	            ifFormat: "%Y/%m/%d",
	            dateType: 'jalali',
	            showsTime: false
	        });
       	});
                
        $("#Form").submit(function(e) {
            e.preventDefault();            
            save(e,'save',true, myClearEntity);           
        });
        
        $(".SearchForm").submit(function(e) {
            e.preventDefault();
            var url = restUrl+'view/'+$('#typeMsg').val();
            if (table) {
            	table.api().ajax.url(url).load(null,true);            
            }
        });                             
    });         
    function myClearEntity() {
    	if(clinics !== null){
        	clinics.clear();
		}    	
		clearEntity();
    }
</script>
<div class="inProgress"></div>
<form role="form" style="direction:rtl;" id="Form">                
    <table style="width:700px">
        <tr>            
            <td>
                <label class="message star">درمانگاه</label>    
                <div class="col-xs-8">
                    <select <?php if($isAdmin == 1) echo 'multiple="multiple"'; ?> style="height:29px" name="data[clinic_id][]" id="clinicId" class="form-control">
                    </select>          
                </div>
            </td>            
        </tr>                                                              
        <tr>            
            <td>
                <label class="message">پیام</label>    
                <div class="col-xs-8">
                    <textarea rows="10" name="data[text]" id="messages" class="form-control" ></textarea>          
                </div>
            </td>            
        </tr>                
        <tr>            
            <td class="btnNav">
                <button type="button" onclick="myClearEntity()" class="btn btn-info new">جدید</button>                
                <button type="button" onclick="fillTable('view',orders,true)" class="btn btn-default view">نمایش</button>      
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
            </tr>                                                 
            <tr>
        		<td>
        			<label>نوع پیام</label>
        			<div class="col-xs-9" >    
	                	<select class="form-control" id="typeMsg">
	                		<option value="1">دریافتی</option>
	                		<option value="2">ارسالی</option>
	                	</select>		                	
	                </div>
        		</td>
        		<td class="btnNav">
                	<button type="button" onclick="clearFilter();" class="btn btn-default filter">حذف فیلتر</button>
                    <button type="submit" class="btn btn-primary search">جستجو</button>                      
                <td>
        	</tr>
        </table>
    </form>
    <?php echo $this->element('gridTools',array('nodelete' => true, 'noedit' => true)); ?>
    <table class="display" style="width:99%" >
        <thead>
            <tr>
                <th>شماره</th>
               	<th>پیام</th>           
               	<th>گیرنده/فرستنده</th>
               	<th>تاریخ</th>
            </tr>
        </thead>        
    </table>
    <?php echo $this->element('gridTools',array('nodelete' => true, 'noedit' => true)); ?>
</div>