<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>                        
        <title>		
            <?php echo $title ?>
        </title>
        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->script('jquery-1.11.1.min');
        // common css and javascript
        echo $this->Html->css('core');
        echo $this->Html->script('core');       
        ?>

        <style>        
            body{
                overflow:hidden;
                background:#f8f8f8;
            }
        </style>
    </head>
    <body>
    	<div style="text-align: center;height: 100vh;">
		    <?php
		    echo $this->Html->image('loader.gif', array('alt' => 'loading', 'style' => 'margin:250px 0', 'class' => 'loader'));
		    ?>
		</div>    	
    	<?php echo $this->fetch('content'); ?>
    </body>   
</html>
