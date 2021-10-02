<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>                        
        <title>		
            <?php $t = AppController::getTitle($this->name.'/'.$this->action); echo $t ? $t : $title ?>
        </title>
        <?php
        echo $this->Html->meta('favicon.ico','/favicon.ico',array('type' => 'icon'));
        echo $this->Html->script('jquery-1.11.1.min');
        // common css and javascript
        echo $this->Html->css('core');
        echo $this->Html->script('core');

        // bootstrap
        echo $this->Html->script('bootstrap.min');
        echo $this->Html->script('bootbox.min');        
        echo $this->Html->css('bootstrap');

        echo $this->Html->script('jquery.dataTables.min');        		
        echo $this->Html->css('jquery.dataTables');
              
        ?>

        <style>        
            body{
                overflow:auto;
            }
        </style>
        <script>
        	$(document).ready(function(){
        		// console.log($(document).css('direction'));
        	});
        </script>
    </head>
    <body style="padding:20px 18px">
        <?php echo $this->fetch('content'); ?>
        <div  id="myModal" class="modal fade" style="font-family: tahoma">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">خطا</h4>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">ببند</button>                        
                    </div>
                </div>
            </div>
        </div>
    </body>   

</html>
