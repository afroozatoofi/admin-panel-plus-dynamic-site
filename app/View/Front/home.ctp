<?php
echo $this->Html->css ( 'popup/magnific-popup' );
echo $this->Html->script ( 'popup/jquery.magnific-popup.min' );

echo $this->Html->css ( 'tabs' );
echo $this->Html->script ( 'tabs' );

// navigator
echo $this->Html->script('jquery.scrollbox');
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.gallery-img a.image, .news a.image').magnificPopup({
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

		$(window).resize(function() {
			width = $('.box-content').innerWidth();
			$('.scroll').css('width', width);
			$('.scroll ul').css('width', width + 1000);
		}).resize();

		$('.news .scroll').scrollbox({
			direction : 'h',
			linear: true, 
			speed : 40,
			delay : 6,
		});

		$('.gallery .scroll').scrollbox({
			direction : 'h',

		});
		//gallery btns navigator
		$('.gallery .als-prev').click(function() {
			$('.gallery .scroll').trigger('forward');
		});
		$('.gallery .als-next').click(function() {
			$('.gallery .scroll').trigger('backward');
		});
		//news btns navigator
		$('.news .als-prev').click(function() {
			$('.news .scroll').trigger('forward');
		});
		$('.news .als-next').click(function() {
			$('.news .scroll').trigger('backward');
		});
	});
</script>
<?php if (! empty ( $news )) { ?>
<div class="news">
	<table style="width: 100%">
		<tr class="tr-title">
			<td>
				<table style="width: 100%">
					<tr class="tr-title">
						<td class="box-title-cnt">تازه ترین خبرها</td>
						<td class="box-bg-cnt">
							<div class="als-btn als-continue" title="لیست اخبار">
								<a class="button" href="<?= $cdnroot ?>news">
									<?php echo $this->Html->image ( 'continue.png' );?>
								</a>
							</div>
							<div class="als-prev als-btn">
								<div></div>
							</div>
							<div class="als-next als-btn">
								<div></div>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="box-content">

				<div class="scroll">

					<ul>
						<?php
	foreach ( $news as $g ) {
		echo "<li>";
		echo '<div class="box-news">';
		echo "<a class='image' href='" . $cdnroot . "img/news/original/" . $g ['image'] . "' title='" . $g ['title'] . "'>
								<img src='" . $cdnroot . "img/news/thumb/" . $g ['image'] . "' title='" . $g ['title'] . "' /></a>";
		echo '<div class="title"><a href="' . $cdnroot . 'news/' . $g ['id'] . '">' . $g ['title'] . '</a></div>';
		echo '<div class="date">' . $g ['jalaliDate'] . '</div>';
		echo '<div class="text">' . nl2br($g ['summary']) . '</div>';
		echo '<a class="more" href="'.$cdnroot.'news/'.$g['id'].'">ادامه مطلب...</a>';
		echo '</div>';	
		echo '</li>';	
	}
	?>
					</ul>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php } ?>
<?php if (! empty ( $gallery )) { ?>
<div class="gallery">
	<table style="width: 100%">
		<tr class="tr-title">

			<td>
				<table style="width: 100%">
					<tr class="tr-title">
						<td class="box-title-cnt">گالری تصاویر</td>
						<td class="box-bg-cnt">
						<div class="als-btn als-continue" title="گالری تصاویر درمانگاه">
								<a class="button" href="<?= $cdnroot ?>gallery">
									<?php echo $this->Html->image ( 'continue.png' );?>
								</a>
							</div>
							<div class="als-prev als-btn">
								<div></div>
							</div>
							<div class="als-next als-btn">
								<div></div>
							</div>
						</td>

					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="box-content gallery-img"
				style="padding-top: 15px; padding-bottom: 0;">

				<div class="scroll">
					<ul>
						<?php
					foreach ( $gallery as $g ) {
						echo "<li><a class='image' href='" . $cdnroot . "img/gallery/original/" . $g ['image'] . "' title='" . $g ['title'] . "'>
						<img src='" . $cdnroot . "img/gallery/thumb/" . $g ['image'] . "' title='" . $g ['title'] . "' /></a></li>";
					}
				?>
					</ul>
				</div>


			</td>
		</tr>
	</table>

</div>
<?php } ?>
<?php if (! empty ( $sections )) { ?>
<div class="section">
	<table style="width: 100%">
		<tr class="tr-title">
			<td>
				<table style="width: 100%">
					<tr class="tr-title">
						<td class="box-title-cnt">بخش ها</td>
						<td class="box-bg-cnt"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="box-content section-tabs">
				<ul id="sections">
					<?php
					$maxLength = 20;
					foreach($sections as $section){
						echo '<li><a ';
						if(mb_strlen($section['title']) > $maxLength){						
				   			echo ' title="'.$section['title'].'" ';
						}
						echo 'href="javascript:;" data-name="#tab-'.$section['id'].'">'.mb_substr($section['title'], 0,$maxLength);
						if(mb_strlen($section['title']) > $maxLength){
							echo '..';
						}
						echo '</a></li>';
					}					 
				?>
				</ul>

				<div id="sections-content" style="display: none">
					<?php
						foreach($sections as $section){
				    		echo '<div id="tab-'.$section['id'].'">'.nl2br($section['description']).'</div>';
						}
					?>
				</div> <?php
							
				?>
			</td>
		</tr>
	</table>
</div>
<?php } ?>
<?php if(!empty($about)){ ?>
<div class="about">
	<table style="width: 100%">
		<tr class="tr-title">
			<td>
				<table style="width: 100%">
					<tr class="tr-title">
						<td class="box-title-cnt">درباره ما</td>
						<td class="box-bg-cnt"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="box-content">
				<table>
					<tr>
						<td style="width: 280px">
							<?php echo $this->Html->image('about.png',array('style'=>'position:absolute;top:-10px')); ?>
						</td>
						<td class="text">
							<div>
								<?= nl2br($about)."..." ?>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<?php } ?>