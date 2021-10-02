<div id="contextMenu" class="dropdown clearfix" style="display:none">
	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
		<?php if(!isset($nonew)){ ?>			
        	<li>        		
        		<a tabindex="-1" onclick="newRecord();">جدید</a>
        	</li>
		<?php } ?>				
		<?php if(!isset($noedit)){ ?>			
        	<li>        		
        		<a tabindex="-1" onclick="editRecord();">ویرایش</a>
        	</li>
		<?php } ?>
		<?php if(!isset($nodelete)){ ?>			
        	<li>        		
        		<a tabindex="-1" onclick="removeRecord();">حذف</a>
        	</li>
		<?php } ?>
		<?php if(isset($btns)){ ?>	
			<?php foreach($btns as $btn){ ?>
				<li>
					<a tabindex="-1" onclick="<?php echo $btn['click'] ?>;"><?php echo $btn['text'] ?></a>
				</li>
			<?php } ?>		        	
		<?php } ?>
	</ul>
</div>