<?php
echo $this->Html->css ( 'popup/magnific-popup' );
echo $this->Html->script ( 'popup/jquery.magnific-popup.min' );
?>
<script type="text/javascript">
$(document).ready(function(){	
	$('.single-news a.image').magnificPopup({
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
<div class="single-news">
	<table style="width: 100%">
		<tr>
			<td>
				<table style="width: 100%">
					<tr class="tr-title">
						<td class="box-title"><?= $news['title'] ?></td>
						<td></td>
						<td class="box-bg"></td>
						<td></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="box-content">
				<div>
				<?php
				echo "<a class='image' href='" . $cdnroot . "img/news/original/" . $news ['image'] . "' title='" . $news ['title'] . "'>
													<img src='" . $cdnroot . "img/news/normal/" . $news ['image'] . "' title='" . $news ['title'] . "' /></a>";
				echo '<div class="date">' . $news ['jalaliDate'] . '</div>';
				echo '<div class="text">' . nl2br ( $news ['text'] ) . '</div>';
				?>
				</div>
			</td>
		</tr>
	</table>
</div>