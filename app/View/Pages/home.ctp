<script type="text/javascript" >
    function showModal() {
        $("#ChangePassForm input").val("");
        $('#changePass').modal();        
    }    
    $(document).ready(function(){
        restUrl = '<?php echo Router::url('/Users/', true) ?>';
        
        $("#ChangePassForm").submit(function(e) {
            e.preventDefault();            
            $.ajax({
                url: restUrl + 'changePass',
                type: "post",
                dataType: "json",
                data: $(e.target).serialize(),                
                beforeSend: function(){                    
                    $('#ChangePassForm .btnNav button').attr('disabled',true);
                },
                success: function() {                    
                    $('#changePass').modal('hide');
                    $("#ChangePassForm input").val("");                     
                },                
                complete: function(){
                    $('#ChangePassForm .btnNav button').attr('disabled',false);
                }
            });
        });                               
    });
</script>
<style type="text/css">
	#ChangePassForm .text{
		color: #16AEC3;
  		line-height: 25px;
  	}
</style>
<div class="wrapper" style="width:100%;height:100%">	
   	
   	<div style="width:100%;position:relative">   		   
	    <!-- Right Menu -->	    
	    <?php
	    	$close = isset($_COOKIE['menu']) && $_COOKIE['menu']=='close' ?  true : false;
	    	$cimage = CakeSession::read ( 'Auth.User.Clinic.image' );	    		    		    
	    ?>
	    <div id="sidebar" class="nano <?php echo $close ? "closeMenu" : "openMenu" ?> noselect" >	    		    
	    	<div class="nano-content">	
	    		<?php if(!empty($cimage)){ ?>
		    		<div id="clinic-img">	    		
		    				<img title="<?php echo $displayName; ?>" src="img/clinic/<?php echo $cimage ?>"  />
		    		</div>
	    		<?php } ?>
	    		<h3 class="toggleMenu">
	    			<span>بستن منو</span>
	    			<span class="toggleMenu <?php echo $close ? "close" : "open" ?>C">
	    				<?php
	    					echo $this->Html->image('DESIGN-'.($close == true ? '03' : '01').'.png');
	    				?>
	    			</span>
	    		</h3>
	            <?php
	            $showImg = Router::url('/Users/', true);
	            $hideImg = Router::url('//', true);	            
	            foreach ($resources as $resource) {
	                if (!empty($resource['children'])) {        
	                    $code = 'ct'.$resource['Resource']['code'];
	                    isset($_COOKIE[$code]) && $_COOKIE[$code]=='hide' ? $show = false : $show = true;                        
	                    echo "<h3 class='toggleLink " . $resource['Resource']['class'] . "' title='".$resource['Resource']['name']."' code='".$code."'>";
	                    echo "<span>" . $resource['Resource']['name'] . "</span>";
	                    echo "<span class='toggleLink ".($show ? "showM" : "hideM")."'>";
	                   	if($show){
	                   		echo $this->Html->image('menu/DESIGN-32.png');
	                   	} else {
	                   		echo $this->Html->image('menu/DESIGN-33.png');
	                   	}
	                    echo "</span>";
	                    echo "</h3>";
	
	                    echo "<ul class='toggle' ".(!$show ? 'style="display:none"' : "" )." >";
	                    foreach ($resource['children'] as $m) {
	                        echo "<li title='" . $m['Resource']['name'] . "' class='" . $m['Resource']['class'] . "'><a href='" . $m['Resource']['url'] . "' ";
	                        echo "englishName='" . $m['Resource']['englishName'] . "' >" . $m['Resource']['name'] . "</a></li>";	                            	                           
	                    }
	                    echo "<div class='clear'></div>";
	                    echo "</ul>";
	                }
	            }
	            ?>                
	        </div>
	    </div>
	    <!-- End of Right Menu -->
	    <!-- Left Frame -->
	    <div id="mainPanel" class="<?php echo $close ? "closeMenu" : "openMenu" ?>" >
	    	<!-- Top Menu -->
			<section id="secondary_bar">		    	   
			    <div class="breadcrumbs_container">
			        <article class="breadcrumbs formPath" style="display:none">
			        	>
			            <a class="current" id="formName"></a>
			            > 
			            <a class="current" id="subFormName"></a>
			        </article>
			    </div>
			    <div class="logout">
					<article class="breadcrumbs" onclick="showModal();">
						<div></div>
						<span style="white-space: nowrap">حساب کاربری</span>
					</article>
					<article class="breadcrumbs" style="padding-right: 0"
						onclick="location.href='<?=$this->Html->url ( array ("controller" => "Users","action" => "logout" ) );?>'">
						<div></div>			        	
			            <span>خروج</span>
			        </article>
				</div>			    
			</section>
			<!-- End of Top Menu -->
	
		    <?php
		    echo $this->Html->image('loader.gif', array('alt' => 'loading', 'style' => 'display:none;margin:150px 0', 'class' => 'loader'));
		    ?>
		    <iframe id="mainForm" style="display:none;height: 94%;" onload="onLoadForm();" style="width" frameborder="0" width="100%" height="100%"></iframe>
	    </div>
	    <!-- End of Left Frame -->
	    
	    <div style="clear:both"></div>
    </div>    
    <div style="clear:both"></div>
</div>

<div id="changePass" class="modal fade" style="font-family: tahoma">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">تغییر گذرواژه</h4>
            </div>
            <div class="modal-body">                
                <form role="form" style="direction:rtl;" id="ChangePassForm">
                    <table style="width: 100%">
                    	<tr>                                        
                            <td>
                                <label>نام و نام خانوادگی</label>    
                                <div class="col-xs-6 text">     
                                	<?php echo $displayName; ?>              
                                </div>
                            </td>       
                            <td width="50%">
                                <label>نام کاربری</label>    
                                <div class="col-xs-6 text">     
                               		<?php echo $username; ?>
                                </div>
                            </td>     
                        </tr>
                        <tr>            
                            <td colspan="2">
                                <label class="message">گذرواژه قبلی</label>    
                                <div class="col-xs-8">     
                                    <input name="data[User][oldPassword]" type="password" class="form-control ltr" >          
                                </div>
                            </td>            
                        </tr>  
                        <tr>            
                            <td colspan="2">
                                <label class="message">گذرواژه جدید</label>    
                                <div class="col-xs-8">     
                                    <input name="data[User][password]" type="password" class="form-control ltr" >          
                                </div>
                            </td>            
                        </tr>  
                        <tr>            
                            <td colspan="2">
                                <label class="message">تکرار گذرواژه</label>    
                                <div class="col-xs-8">     
                                    <input name="data[User][confirmPassword]" type="password" class="form-control ltr" >          
                                </div>
                            </td>            
                        </tr>  
                        <tr>            
                            <td class="btnNav" colspan="2">                                                                                
                                <button type="button" class="btn btn-default cancel" data-dismiss="modal">لغو</button>                        
                                <button type="submit" class="btn btn-primary save">ثبت</button>                                                      
                            </td>
                        </tr>                        
                    </table>    
                </form>            
            </div>            
        </div>
    </div>
</div>