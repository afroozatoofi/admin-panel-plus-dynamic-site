<?php
echo $this->Html->css ( 'popup/magnific-popup' );
echo $this->Html->script ( 'popup/jquery.magnific-popup.min' );
?>
<script type="text/javascript">
$(document).ready(function(){	
	$('.bio a.image').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		closeBtnInside: false,
		fixedContentPos: true,
		mainClass: 'mfp-no-margins mfp-with-zoom',
		image: {
			verticalFit: true
		},
		zoom: {
			enabled: true,
			duration: 300 // don't foget to change the duration also in CSS
		}
	});
});
</script>
<?php if (! empty ( $bio )) { ?>
<div class="bio">
	<table style="width: 100%">
		<tr>
			<td>
				<table style="width: 100%">
					<tr class="tr-title">
						<td class="box-title-cnt">بیوگرافی</td>
						<td class="box-bg-cnt"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="box-content">
				<table style="width: 100%">
							<?php
	foreach ( $bio as $g ) {
		?>
					<tr class="box-bio">
						<td style="width: 200px">
			<?php
		echo "<a class='image' href='" . $cdnroot . "img/bio/original/" . $g ['image'] . "' title='" . $g ['name'] . "'>
								<img src='" . $cdnroot . "img/bio/thumb/" . $g ['image'] . "' title='" . $g ['name'] . "' /></a>";
		?>
						</td>
						<td style="vertical-align: top">
							<div>
								<label>نام پزشک: </label> <span><?= $g ['name'] ?></span>
							</div>
							<div>
								<label>بیوگرافی: </label> <span><?= nl2br( $g ['detail']) ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr style="margin: 10px 0; border-bottom: 2px dotted #cccccc;border-top: none" />
						</td>
					</tr>
					<?php
	}
	?>
				</table>
			</td>
		</tr>
	</table>
</div>
<?php } ?>