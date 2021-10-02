<?php
echo $this->Html->script ( 'calendar/calendar' );
echo $this->Html->script ( 'calendar/calendar-setup' );
echo $this->Html->script ( 'calendar/jalali' );
echo $this->Html->script ( 'calendar/lang/calendar-fa' );
echo $this->Html->css ( 'calendar/skins/calendar-blue' );
?>
<style>
#grid tr td:nth-child(1) {
	padding-right: 15px;
}

#grid .display tr td:nth-child(7), #grid .display tr td:nth-child(6) {
	direction: ltr;
}
</style>
<script type="text/javascript">
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/queue/', true) ?>';
        columns = [           
            { "data": "id", "visible" : false },
            { "data": "payment", "width": "10px"},
            { "data": "clinic", "width": "60px"},
            { "data": "section", "width": "60px"},
            { "data": "name", "width": "60px"},
            { "data": "age", "width": "35px"},
            { "data": "sex", "width": "35px"},                                
            { "data": "telephone", "width": "70px"},
            { "data": "registerDate", "width": "40px"},
            { "data": "visitDate", "width": "40px"},
            { "data": "desc", "width": "180px",sortable: false},
            { "data": "tracking", "width": "40px"}
        ];                
        orders = [[0, "desc"]]; 
        fillTable('view',orders,true);
        fillCombo("#sections",restUrl+"loadSections",null,'id','title','....');
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
    });
    function addPayment() {
    	var id = gridRow('id');
    	if (!id.length)
    		return;
    	bootbox.confirm("آیا از پرداخت مطئن هستید؟", function(result) {
    		if (!result)
    			return;
    		$.ajax({
    			url : 'addPayment/' + id[0],
    			type : 'delete',
    			beforeSend : function() {
    				progress(true);
    			},
    			success : function() {
    				table.api().ajax.reload(null, false);
    			},
    			complete : function() {
    				progress(false);
    			}
    		});
    	});
    } 
    function removePayment() {
    	var id = gridRow('id');
    	if (!id.length)
    		return;
    	bootbox.confirm("آیا از عدم پرداخت مطمئن هستید؟", function(result) {
    		if (!result)
    			return;
    		$.ajax({
    			url : 'removePayment/' + id[0],
    			type : 'delete',
    			beforeSend : function() {
    				progress(true);
    			},
    			success : function() {
    				table.api().ajax.reload(null, false);
    			},
    			complete : function() {
    				progress(false);
    			}
    		});
    	});
    }
</script>
<div class="inProgress"></div>
<div id="grid" style="display: none">
	<form class="SearchForm" id="SearchForm">
		<table class="display" style="width: 850px">
			<tr>
				<td style="width: 30%"><label>از تاریخ</label>
					<div class="col-xs-6">
						<input name="registerDate>=" readonly="readonly" type="text"
							class="form-control ltr shdate">
					</div></td>
				<td style="width: 25%"><label>تا تاریخ</label>
					<div class="col-xs-6">
						<input name="registerDate<=" readonly="readonly" type="text"
							class="form-control ltr shdate">
					</div></td>
				<td style="width: 25%"><label>بخش</label>
					<div class="col-xs-6">
						<select name="section_id" id="sections" class="form-control"></select>
					</div></td>
				<td></td>
			</tr>
			<tr>
				<td><label>نام و نام خانوادگی</label>
					<div class="col-xs-6">
						<input name="name" type="text" class="form-control">
					</div></td>
				<td><label>شماره تماس</label>
					<div class="col-xs-6">
						<input name="telephone" type="text" class="form-control">
					</div></td>
				<td><label>جنسیت</label>
					<div class="col-xs-6">
						<select name="sex" class="form-control">
							<option value="-1">....</option>
							<option value="1">مرد</option>
							<option value="2">زن</option>
						</select>
					</div></td>
					<td></td>
			</tr>
			<tr>

				<td><label>سن</label>
					<div class="col-xs-6">
						<input name="age" type="number" class="form-control">
					</div></td>
				<td><label>وضعیت پرداخت</label>
					<div class="col-xs-6">
						<select name="payment" class="form-control">
							<option value="-1">همه</option>
							<option value="1">پرداخت شده</option>
						</select>
					</div></td>
				<td><label>شماره پیگیری</label>
					<div class="col-xs-6">
						<input name="tracking" type="text" class="form-control">
					</div></td>
				<td class="btnNav" style="width: 200px">
					<button type="button" onclick="clearFilter();"
						class="btn btn-default filter">حذف فیلتر</button>
					<button type="submit" class="btn btn-primary search">جستجو</button>
				
				<td>
			
			</tr>
		</table>
	</form>
    <?php echo $this->element('gridTools',array('noedit'=>true,'nodelete'=>true,'nonew'=>true)); ?>
    <table class="display" style="width:99%">
		<thead>
			<tr>
				<th>شماره</th>
				<th>پرداخت</th>
				<th>درمانگاه</th>
				<th>بخش</th>
				<th>نام و نام خانوادگی</th>
				<th>سن</th>
				<th>جنسیت</th>
				<th>تلفن تماس</th>
				<th>تاریخ ثبت نام</th>
				<th>تاریخ مراجعه</th>
				<th>توضیحات</th>
				<th>شماره پیگیری</th>
			</tr>
		</thead>
	</table>
    <?php echo $this->element('gridTools',array('noedit'=>true,'nodelete'=>true,'nonew'=>true)); ?>
</div>