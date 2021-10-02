<?php
echo $this->Html->css ( 'popup/magnific-popup' );
echo $this->Html->script ( 'popup/jquery.magnific-popup.min' );
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.gallery-img a.image').magnificPopup({
			type : 'image',
			closeOnContentClick : true,
			closeBtnInside : false,
			fixedContentPos : true,
			mainClass : 'mfp-no-margins mfp-with-zoom',
			image : {
				verticalFit : true
			},
			zoom : {
				enabled : true,
				duration : 300
			// don't foget to change the duration also in CSS
			}
		});
	});
</script>
<?php if (! empty ( $gallery )) { ?>
<div class="gallery">
	<table style="width: 100%">

		<tr>
			<td>
				<table style="width: 100%">
					<tr class="tr-title">
						<td class="box-title-cnt">گالری تصاویر</td>
						<td class="box-bg-cnt"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="box-content gallery-img"
				style="padding-top: 15px"><?php
					foreach ( $gallery as $g ) {
						echo "<a class='image' href='" . $cdnroot . "img/gallery/original/" . $g ['image'] . "' title='" . $g ['title'] . "'>
								<img src='" . $cdnroot . "img/gallery/thumb/" . $g ['image'] . "' title='" . $g ['title'] . "' /></a>";
					}
				?>
				<div class="nav">
					<a
						class="button<?php echo $prev ? '" href="'.$cdnroot.'gallery/page/'.($page-1).'"' : ' disabled"'; ?>">قبلی</a>
					<a
						class="button<?php echo $next ? '" href="'.$cdnroot.'gallery/page/'.($page+1).'"' : ' disabled"'; ?>">بعدی</a>
				</div></td>
		</tr>
	</table>
</div>
<?php } ?>