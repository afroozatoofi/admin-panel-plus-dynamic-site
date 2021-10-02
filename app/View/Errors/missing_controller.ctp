<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
    <?php echo $this->Html->charset(); ?>        
    <title>صفحه مورد نظر یافت نشد</title>
    <?php
				echo $this->Html->meta ( 'favicon.ico', '/favicon.ico', array (
						'type' => 'icon' 
				) );
				echo $this->Html->css ( 'front-core' );
				?>      
</head>
<body>
	<div class="menu-wrapper">
		<div class="menu">			
		</div>
	</div>		
	<div class="wrapper">
		<div class="content error">					
			صفحه مورد نظر یافت نشد
		</div>
	</div>
</body>
</html>
