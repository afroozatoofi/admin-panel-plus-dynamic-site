<?php
	echo $this->Html->script('invalidInput');
?>
<script>
    $(document).ready(function() {
        restUrl = '<?php echo Router::url('/', true) ?>';
        $('.creload').on('click', function() {
            var mySrc = $(this).prev().attr('src');
            var glue = '?';
            if (mySrc.indexOf('?') != -1) {
                glue = '&';
            }
            $(this).prev().attr('src', mySrc + glue + new Date().getTime());
            return false;
        });
        var isInIframe = (window.location != window.parent.location) ? true : false;
        var warnings = { emptyText: "این فیلد نمی تواند خالی باشد!"}
        InvalidInput(document.getElementById("UserUsername"), warnings);
        InvalidInput(document.getElementById("UserPassword"), warnings);
        InvalidInput(document.getElementById("UserSecurityCode"), warnings);
        $("#SignUpForm").submit(function(e) {        	                        
            e.preventDefault();
            $.ajax({
                url: 'login',
                type: "post",
                dataType: "json",
                data: $(e.target).serialize(),
                beforeSend: function() {
                    progress(true);
                },
                success: function(url) {                    
                    if(isInIframe){
                        window.parent.location.reload();  
                    } else {
                       	window.location = url.substr(1);
                    }                    
                },
                error: function() {                    
                    $('#UserSecurityCode,#UserPassword').val('');
                    $('.creload').click();
                },
                complete: function() {
                    progress(false);
                }
            });
        });
    });
</script>
<style>
	body{background-color:#EFF0F0 !important}
</style>

<div class="inProgress"></div>
<div class="users form" style="width: 350px;margin: 8% auto 0 auto;">
    <form id="SignUpForm" method="post" accept-charset="utf-8" >    	
    	<?php echo $this->Html->image('logo.jpg',array('style'=>'width:100%')); ?>
    	<div class="wrapper">
	        <table>               
	            <tr>
	                <td width="50px">
	                    <label class="message">نام کاربری</label>                        
	                </td>
	                <td>
	                    <input tabindex="1" class="form-control ltr" name="data[User][username]" maxlength="50" type="text" id="UserUsername" required="required">
	                </td>
	            </tr>
	            <tr>
	                <td>                        
	                    <label class="message">گذرواژه</label>
	                </td>
	                <td>
	                    <input tabindex="2" name="data[User][password]" type="password" id="UserPassword" required="required" class="form-control ltr">
	                </td>
	            </tr>
	            <tr>
	                <td style="vertical-align:bottom">
	                    <label class="message">کد امنیتی</label>
	                </td>
	                <td>
	                    <?php $this->Captcha->render(); ?>
	                    <input style="margin-top:3px" tabindex="3" name="data[User][security_code]" autocomplete="off" class="form-control ltr" 
	                           type="text" value="" id="UserSecurityCode" required="required">
	                </td>
	            </tr>
	            <tr>                    
	                <td colspan="2" style="text-align: left">                        
	                    <button type="submit" tabindex="4" class="btn btn-default">ورود</button>
	                </td>
	            </tr>               
	        </table>
        </div>
    </form>
</div>