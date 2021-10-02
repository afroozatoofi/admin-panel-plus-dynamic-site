<?php if(empty($this->request->query['lov'])){ ?>							        		
<div class="gridtools" >
	<?php if(!isset($nonew)){ ?>	
		<button type="button" onclick="newRecord();" class="btn btn-info new">جدید</button>
	<?php } ?>
	<?php if(!isset($noedit)){ ?>
		<button type="button" onclick="editRecord();" class="btn btn-primary save">ویرایش</button>
	<?php } ?>
	<?php if(!isset($nodelete)){ ?>
		<button type="button" onclick="removeRecord()" class="btn btn-danger delete">حذف</button>
	<?php } ?>		
	<?php if(isset($btns)){ ?>
		<?php foreach($btns as $btn){ ?>
			<button type="button" onclick="<?php echo $btn['click'] ?>;" class="btn <?php echo $btn['class'] ?>">			
        		<?php echo $btn['text'] ?>
			</button>		
		<?php } ?>
	<?php } ?>
</div>		
<?php require_once('contextMenu.php'); ?>			        			   	   		     
<?php } ?>