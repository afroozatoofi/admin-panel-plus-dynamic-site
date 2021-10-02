<?php
echo $this->Html->script ( 'selectize.min' );
echo $this->Html->css ( 'selectize.default' );
?>
<style>
#grid tr td:nth-child(2) {
	text-align: left;
}
</style>
<script type="text/javascript">
	$(document).ready(function(){                
	    restUrl = '<?php echo Router::url('/Users/', true) ?>';
	    columns = [           
	        { "data": "id", "visible" : false },
	        { "data": "clinic","width": "150px"},
	        { "data": "username" },            
	        { "data": "name" },
	        { "data": "family" },
	        { "data": "isEnabled" }
	    ];
	
	    $("#Form").submit(function(e) {
	        e.preventDefault();            
	        save(e,'save');           
	    });                               
	    fillCombo('#UserGroup',restUrl+'getAllGroup',null,'id','name',null, function(){
	    	$selectize = $('#UserGroup').removeClass('form-control').selectize();        	
        	userGroups = $selectize[0].selectize;	                	        	    		       
	    });
	    
	    $(".SearchForm").submit(function(e) {
	        e.preventDefault();            
	        if (table) {
	            table.api().ajax.reload(null, true);
	        }
	    });
	});    
	function _editRecord(){
 	   var id = table.fnGetData($(this).index())['id'];    
    	loadById(id,null,null,setEntity);
	}
    function editRecord(){
		var id = gridRow('id');
    	if(!id.length) return;
    	loadById(id[0],null,null,setEntity);
	}
	function setEntity(entity){
		myClearEntity();
		
		model = 'User';				
		for (prop in entity[model]) {
			var sel = '[name="data[' + model + '][' + prop + ']"]';
			var val = entity[model][prop];
			if (val)
				$('input[type!=checkbox]'+sel+',select'+sel+',textarea'+sel).val(val).change();				
			$('input[type=checkbox]' + sel).prop('checked',
					val == 1 ? true : false);
		}
		model = 'UserGroup';
		$(entity[model]).each(function(i,r){                                                           
			userGroups.addItem(r.id);	
        });				            
        $('#clinicId').val(entity.Clinic.id);
       	$('#clinicName').val(entity.Clinic.name);
	}     
	function showClinic(){	    	    
    	showModalForm('<?php echo Router::url('/Clinics/crud?lov=1', false) ?>',function(e){
			$('#clinicId').val(e.id);
			$('#clinicName').val(e.name);
		});
    }
    function showClinic_search(){	    	    
    	showModalForm('<?php echo Router::url('/Clinics/crud?lov=1', false) ?>',function(e){
			$('#clinicId_search').val(e.id);
			$('#clinicName_search').val(e.name);
		});
    }
    function myClearEntity() {       
        userGroups.clear();		
        clearEntity();   
        $('#isEnabled').prop('checked',true); 	    
    }
</script>
<div class="inProgress"></div>
<form role="form" style="direction: rtl;" id="Form">
	<input id="id" name="data[User][id]" type="hidden" value="">
	<table style="width: 700px">
		<tr>
			<td><label class="message star">نام کاربری</label>
				<div class="col-xs-8">
					<input name="data[User][username]" type="text"
						class="form-control ltr">
				</div></td>
		</tr>
		<tr>
			<td><label class="message star">گذرواژه</label>
				<div class="col-xs-8">
					<input name="data[User][password]" type="text"
						class="form-control ltr">
				</div></td>
		</tr>
		<tr>
			<td><label class="message">نام</label>
				<div class="col-xs-8">
					<input name="data[User][name]" type="text" class="form-control">
				</div></td>
		</tr>
		<tr>
			<td><label class="message">نام خانوادگی</label>
				<div class="col-xs-8">
					<input name="data[User][family]" type="text" class="form-control">
				</div></td>
		</tr>
		<tr>
			<td><label class="message">گروه کاربری</label>
				<div class="col-xs-8">
					<select multiple='multiple' style="height: 100px"
						name="data[UserGroup][id][]" id="UserGroup" class="form-control">
					</select>
				</div></td>
		</tr>
		<tr>
			<td><label class="message star">درمانگاه</label>
				<div class="col-xs-8">
					<input name="data[User][clinic_id]" id="clinicId" type="hidden"> <input
						id="clinicName" type="text" class="form-control"
						readonly="readonly">
            	<?php
													echo $this->Html->image ( 'DESIGN-38.png', array (
															'onclick' => 'showClinic()',
															'class' => 'lov ltr' 
													) );
													?>
            </div></td>
		</tr>
		<tr>
			<td><label class="message" for="isEnabled">فعال</label>
				<div class="col-xs-8" style="text-align: right">
					<div class="checkbox">
						<label> <input id="isEnabled" checked="checked"
							name="data[User][isEnabled]" type="checkbox" value="1">
						</label>
					</div>
				</div></td>
		</tr>
		<tr>
			<td class="btnNav">
				<button type="button" onclick="myClearEntity()"
					class="btn btn-info new">جدید</button>
				<button type="button" onclick="fillTable('view')"
					class="btn btn-default view">نمایش</button>
				<button type="submit" class="btn btn-primary save">ثبت</button>
			</td>
		</tr>
	</table>
</form>
<div id="grid" style="display: none">
	<form class="SearchForm" id="SearchForm">
		<table class="display" style="width: 700px">
			<tr>
				<td><label class="message">نام کاربری</label>
					<div class="col-xs-8">
						<input name="username" type="text" class="form-control ltr">
					</div></td>
				<td><label class="message">درمانگاه</label>
					<div class="col-xs-8">
						<input id="clinicId_search" name="clinic_id" type="hidden"> <input
							id="clinicName_search" type="text" class="form-control"
							readonly="readonly">
	                	<?php
																		echo $this->Html->image ( 'DESIGN-38.png', array (
																				'onclick' => 'showClinic_search()',
																				'class' => 'lov ltr' 
																		) );
																		?>
	                </div></td>
			</tr>
			<tr>
				<td><label class="message">نام</label>
					<div class="col-xs-8">
						<input name="name" type="text" class="form-control">
					</div></td>
				<td><label class="message">نام خانوادگی</label>
					<div class="col-xs-8">
						<input name="family" type="text" class="form-control">
					</div></td>
			</tr>
			<tr>
				<td><label class="message" for="isEnabled2">فعال</label>
					<div class="col-xs-8">
						<input id="isEnabled2" name="isEnabled" type="checkbox" value="1">
					</div></td>
				<td class="btnNav">
					<button type="button" onclick="clearFilter();"
						class="btn btn-default filter">حذف فیلتر</button>
					<button type="submit" class="btn btn-primary">جستجو</button>
				
				<td>
			
			</tr>
		</table>
	</form>
    <?php echo $this->element('gridTools',array('nodelete'=>true)); ?>
    <table class="display" style="width: 99%">
		<thead>
			<tr>
				<th>شماره</th>
				<th>درمانگاه</th>
				<th>نام کاربری</th>
				<th>نام</th>
				<th>نام خانوادگی</th>
				<th>وضعیت</th>
			</tr>
		</thead>
	</table>
    <?php echo $this->element('gridTools',array('nodelete'=>true)); ?>
</div>