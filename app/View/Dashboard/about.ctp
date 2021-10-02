<script type="text/javascript">
    $(document).ready(function(){                
        restUrl = '<?php echo Router::url('/about_us/', true) ?>';        
        $("#Form").submit(function(e) {
            e.preventDefault();            
            save(e,'save');           
        });               
        
        loadById(-1);
    });
</script>
<div class="inProgress"></div>
<form role="form" style="direction:rtl;" id="Form">      
    <input id="id" name="data[AboutUs][id]" type="hidden" value="" >          
    <table style="width:700px">
    	<tr>            
            <td>
                <label class="message">درمانگاه</label>    
                <div class="col-xs-8">     
                	<?php echo $clinic; ?>      
                </div>
            </td>            
        </tr>
        <tr>            
            <td>
                <label class="message star">خلاصه درباره</label>    
                <div class="col-xs-8">     
                    <textarea name="data[AboutUs][summary]" rows="6" class="form-control" ></textarea>          
                </div>
            </td>            
        </tr>    
        <tr>            
            <td>
                <label class="message star">درباره</label>    
                <div class="col-xs-8">     
                    <textarea name="data[AboutUs][description]" rows="15" class="form-control" ></textarea>          
                </div>
            </td>            
        </tr>                
        <tr>            
            <td class="btnNav">                                                     
                <button type="submit" class="btn btn-primary save">ویرایش</button>                      
            </td>
        </tr>
    </table>       
</form>