<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>		
            <?php echo $title; ?>
        </title>
        <?php
        echo $this->Html->meta('favicon.ico','/favicon.ico',array('type' => 'icon'));
        echo $this->Html->script('jquery-1.11.1.min');
        echo $this->Html->script('jquery-migrate-1.2.1');        
        
        echo $this->Html->css('layout');
        echo $this->Html->css('menu');
        echo $this->Html->script('show.hide.menu');
        
        // common css and javascript
        echo $this->Html->css('core');
        echo $this->Html->script('core');

        // bootstrap
        echo $this->Html->script('bootstrap.min');
        echo $this->Html->css('bootstrap');
        
        // nano scroller
        echo $this->Html->script('jquery.nanoscroller.min');
        echo $this->Html->css('nanoscroller');

        // hashchange plugin 
        echo $this->Html->script('jquery.ba-hashchange.min');
        ?>
        <script type="text/javascript" >
            $(function() {
                $('#sidebar a').click(function() {
                    clickMenu(this);
                    return false;
                });
                $(window).hashchange(checkHash).hashchange();
                $(".nano").nanoScroller();
            });
        </script>
        <style>
        	.nano { width: 500px; height: 100vh }					        	
        </style>
    </head>
    <body>
        <?php echo $this->fetch('content'); ?>
        <div  id="myModal" class="modal fade" >
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
        <div  id="loginModal" class="modal fade" >
            <div class="modal-dialog" style="width:640px;height:540px">            		      
		        <div class="modal-content" style="height:100%" >		        	
                    <iframe width="100%" src="" frameborder="0" height="100%" id="loginFrame" ></iframe>
		        </div>
		    </div>
        </div>
    </body>
</html>
