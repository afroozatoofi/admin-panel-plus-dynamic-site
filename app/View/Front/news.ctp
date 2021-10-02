<?php
echo $this->Html->css ( 'popup/magnific-popup' );
echo $this->Html->script ( 'popup/jquery.magnific-popup.min' );
?>
<script type="text/javascript">
$(document).ready(function(){	
	$('.news a.image').magnificPopup({
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
<?php if (! empty ( $news )) { ?>
<div class="news">
	<table style="width: 100%">
		<tr>
			<td>
				<table style="width: 100%">
					<tr class="tr-title">
						<td class="box-title-cnt">اخبار</td>
						<td class="box-bg-cnt">
							
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="box-content">
				<?php
	foreach ( $news as $g ) {
		echo '<div class="box-news">';
		echo "<a class='image' href='" . $cdnroot . "img/news/original/" . $g ['image'] . "' title='" . $g ['title'] . "'>
								<img src='" . $cdnroot . "img/news/thumb/" . $g ['image'] . "' title='" . $g ['title'] . "' /></a>";
		echo '<div class="title"><a href="' . $cdnroot . 'news/' . $g ['id'] . '">' . $g ['title'] . '</a></div>';
		echo '<div class="date">' . $g ['jalaliDate'] . '</div>';
		echo '<div class="text">' . nl2br ( $g ['summary'] ) . '</div>';
		echo '<a class="more" href="'.$cdnroot.'news/'.$g['id'].'">ادامه مطلب...</a>';
		echo '</div>';		
	}
	?>
				<div class="nav">
					<a
						class="button<?php echo $prev ? '" href="'.$cdnroot.'news/page/'.($page-1).'"' : ' disabled"'; ?>">قبلی</a>
					<a
						class="button<?php echo $next ? '" href="'.$cdnroot.'news/page/'.($page+1).'"' : ' disabled"'; ?>">بعدی</a>
				</div>

			</td>
		</tr>
	</table>
</div>
<?php } ?>