<?php
echo $this->Html->script ( 'calendar/calendar' );
echo $this->Html->script ( 'calendar/calendar-setup' );
echo $this->Html->script ( 'calendar/jalali' );
echo $this->Html->script ( 'calendar/lang/calendar-fa' );
echo $this->Html->css ( 'calendar/skins/calendar-blue' );

echo $this->Html->css ( 'popup/magnific-popup' );
echo $this->Html->script ( 'popup/jquery.magnific-popup.min' );

echo $this->Html->script ( 'core' );

?>
<style type="text/css">
td.captcha a {
	vertical-align: bottom;
}

.mfp-content {
	width: 500px !important;
}
</style>
<script type="text/javascript">
	$(document).ajaxError(function(e, request) {
		if (request.status === 200)
			return;		
		text = request.responseText;
		showExp(text);
	});
    $(document).ready(function(){
    	restUrl = '<?= $cdnroot ?>';
		$("#Form").submit(function(e) {
			e.preventDefault();
			save(e, 'saveQueue');
		});

		fillCombo("#sections",restUrl+"loadSections/"+<?= $clinic['id'] ?>,null,'id','title','....');
		$('.shdate').each(function(i, dd) {
			Calendar.setup({
				inputField : dd,
				ifFormat : "%Y/%m/%d %H:%M",
				dateType : 'jalali',
				showsTime : true
			});
		});

		$('.popup-modal').magnificPopup({
			type : 'inline',
			preloader : false,
			focus : '#username',
			modal : true
		});
		$(document).on('click', '.popup-modal-dismiss', function(e) {
			e.preventDefault();
			$.magnificPopup.close();
		});

		$('.creload').on('click', function() {
			var mySrc = $(this).prev().attr('src');
			var glue = '?';
			if (mySrc.indexOf('?') != -1) {
				glue = '&';
			}
			$(this).prev().attr('src', mySrc + glue + new Date().getTime());
			return false;
		});

	});
	function save(e, url) {
		$.ajax({
			url : restUrl + url,
			type : "post",
			dataType : "json",
			data : $(e.target).serialize() + "&security_code="
					+ $('#security_code').val(),
			beforeSend : function() {
				$('#submit').attr('disabled', true);
				$('#result').html('').hide();
			},
			success : function(res) {
				clearEntity();
				$('.creload').click();
				$('#result').html(res.result).show();				
			},
			error : function(request) {
				showExp(request.responseText);
				$('#submit').attr('disabled', false);
				$('.creload').click();
				$('#security_code').val('');
			},
			complete : function() {
				$('#submit').attr('disabled', false);
				$('#security_code').val('');
			}
		});
	}
	function showExp(msg) {
		if (!msg) {
			return;
		}
		var error = getException(msg);
		$('#error-modal').html(error);
		// open popup
		$.magnificPopup.open({
			items : {
				src : '#error-modal'
			},
			type : 'inline'
		});
	}
	function getException(msg) {
		var json;
		try {
			json = JSON.parse((convert(msg)));
		} catch (e) {
			alert(e);
		}
		var error = '<ul>';
		$.each(json, function(i, f) {
			$.each(f, function(j, e) {
				error += '<li>' + e + '</li>';
			});
		});
		error += '</ul>';
		return error;
	}
	function convert(str) {
		str = str.replace(/&amp;/g, "&");
		str = str.replace(/&gt;/g, ">");
		str = str.replace(/&lt;/g, "<");
		str = str.replace(/&quot;/g, "\"");
		str = str.replace(/&#039;/g, "'");
		return str;
	}
	function clearEntity() {
		$('input[type!=submit],textarea').val('');
		$('select option').removeAttr('selected');
	}
</script>
<div class="queue">
	<form role="form" style="direction: rtl;" id="Form">
		<table style="width: 100%">
			<tr>
				<td>
					<table style="width: 100%">
						<tr class="tr-title">
							<td class="box-title-cnt">ثبت نوبت</td>
							<td class="box-bg-cnt"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="box-content">
					<table
						style="border-spacing: 3px; border-collapse: separate; width: 500px">
						<tr>
							<td>درمانگاه</td>
							<td><?= $clinic['name'] ?></td>
						</tr>
						<tr>
							<td>بخش</td>
							<td><select id="sections" name="section_id"></select></td>
						</tr>
						<tr>
							<td width="180px">نام و نام خانوادگی</td>
							<td><input maxlength="64" name="name" type="text" /></td>
						</tr>
						<tr>
							<td>سن</td>
							<td><input name="age" type="number" /></td>
						</tr>
						<tr>
							<td>جنسیت</td>
							<td><select name="sex">
									<option value="">....</option>
									<option value="1">مرد</option>
									<option value="2">زن</option>
							</select></td>
						</tr>
						<tr>
							<td>تلفن تماس</td>
							<td><input name="telephone" maxlength="20" type="text"
								style="direction: ltr; text-align: left"></td>
						</tr>
						<tr>
							<td>تاریخ مراجعه</td>
							<td><input name="visitDate" readonly="readonly" type="text"
								class="shdate" style="direction: ltr; text-align: left"></td>
						</tr>
						<tr>
							<td style="vertical-align: top">توضیحات (اختیاری)</td>
							<td><textarea maxlength="1024" name="desc" rows="8"
									style="width: 100%"></textarea></td>
						</tr>
						<tr>
							<td></td>
							<td class="captcha"><?php echo $this->Captcha->render(); ?>
							</td>
						</tr>
						<tr>
							<td>کد امنیتی</td>
							<td><input maxlength="5" id="security_code" type="text" /></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left"><input type="submit"
								id="submit" value="ثبت" /></td>
						</tr>
					</table>
					<div id="result" style="color: #1083D6; display: none"></div>
				</td>
			</tr>
		</table>
	</form>
</div>
<div id="error-modal" class="white-popup-block mfp-hide">
	<h1>خطا</h1>
</div>