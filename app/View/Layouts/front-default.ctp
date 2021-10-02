<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<?php echo $this->Html->charset(); ?>
<title><?=$title?></title>
<?php
echo $this->Html->meta ( 'favicon.ico', '/favicon.ico', array (
		'type' => 'icon' 
) );
echo $this->Html->script ( 'jquery-1.11.1.min' );
echo $this->Html->script ( 'jquery-migrate-1.2.1' );

echo $this->Html->css ( 'front-core' );

echo $this->Html->script ( 'nivo/jquery.nivo.slider.pack' );
echo $this->Html->css ( 'nivo/nivo-slider' );
echo $this->Html->css ( 'nivo/themes/default/default' );
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
</head>
<script type="text/javascript">
	<?php
	
	$lat = $contact ['latitude'];
	$lon = $contact ['longitude'];
	$map = ! empty ( $lat ) && ! empty ( $lon );
	if (! $map) {
		// Shiraz
		$lat = 29.611670115197377;
		$lon = 52.535247802734375;
	}
	?>
	$(window).load(function() {
		<?php if(!empty($sliders)){?>
		$('#slider').nivoSlider({
			effect : 'slideInLeft',
			pauseTime : 8000,
			animSpeed : 400,
			pauseOnHover : true
		});
		<?php } ?>
		getGoogleMap();
	});
	function getGoogleMap() {
		var myLatlng = new google.maps.LatLng(<?= $lat ?>, <?= $lon ?>);
		var mapOptions = {
			zoom : <?= $map ? 15 : 12; ?>,
			center : myLatlng
		};
		var map = new google.maps.Map(document.getElementById('map-canvas'),
				mapOptions);
		<?php if($map){ ?>
		var marker = new google.maps.Marker({
			position : myLatlng,
			map : map,
			title : "<?= $clinic['name'] ?>"
		});
		<?php } ?>
	}

	var scrollTrigger = 57;
	backToTop = function() {
		var scrollTop = $(window).scrollTop();
		if (scrollTop > scrollTrigger) {
			$('#back-to-top').fadeIn();
		} else {
			$('#back-to-top').fadeOut();
		}
	};
	
	headerHeight = 152;//$(".menu-wrapper1").innerHeight() + $(".header-wrapper").innerHeight() + $(".menu-wrapper").innerHeight() ;
	fixMenu = function() {
		var scrollTop = $(window).scrollTop();
		if (scrollTop > headerHeight) {
			$('.menu-wrapper').addClass("menu-fix").css('opacity',.8);
		} else {
			$('.menu-wrapper').removeClass("menu-fix").css('opacity',1);
		}
	};
	$(window).on('scroll', function() {
		backToTop();
		fixMenu();
	});
	$(document).ready(function() {
		backToTop();
		$('#back-to-top').on('click', function(e) {
			$('html,body').animate({
				scrollTop : 0
			}, 500);
		});
	});
</script>
<body>
	<div class="menu-wrapper1"></div>
	<div class="header-wrapper">
		<div class="header">
			<span class="helper"></span>

			<?php if(!empty($clinic['image'])){ ?>
			<a href="<?= $cdnroot ?>"> <?php echo $this->Html->image('clinic/'.$clinic['image'],array('class'=>'header-logo')); ?>
			</a>
			<?php } ?>
			<div class="contact">
				<span class="helper"></span>
				<table>
					<tr>
						<?php if(!empty($contact['email'])) { ?>
						<td>
							<?php echo $this->Html->image('mail.png'); ?>
						</td>
						<td>
							<div style="color: #669900">پست الکترونیک :</div>
							<div>
								<span> <?php echo $contact['email']; ?>
								</span>
							</div>
						</td>
						<?php } ?>
						<?php if(!empty($contact['Tels'])) { ?>
						<td style="width: 20px"></td>
						<td>
							<?php echo $this->Html->image('tel.png'); ?>
						</td>
						<td>
							<div style="color: #669900">تلفن تماس :</div>
							<div>
								<span> <?php echo $contact['Tels'][0]['telephone']?>
								</span>
							</div>
						</td>
						<?php } ?>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="menu-wrapper">
		<div class="menu">
			<ul class="<?= empty($this -> action) ? 'home' : $this -> action; ?>">
				<li class="home"><a href="<?= $cdnroot ?>">صفحه اصلی</a></li>
				<?php if(empty($index)){ ?>
				<li class="biography"><a href="<?= $cdnroot ?>biography">بیوگرافی پزشکان</a></li>
				<li class="queue"><a href="<?= $cdnroot ?>queue">نوبت دهی اینترنتی</a></li>
				<li class="news"><a href="<?= $cdnroot ?>news">اخبار درمانگاه</a></li>
				<li class="gallery"><a href="<?= $cdnroot ?>gallery">گالری تصاویر</a></li>
				<li class="about"><a href="<?= $cdnroot ?>about">درباره ما</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<?php if(!empty($sliders)){ ?>
	<div class="slider">
		<div class="slider-wrapper theme-default">
			<div id="slider" class="nivoSlider">
				<?php
		foreach ( $sliders as $slider ) {
			echo $this->Html->image ( 'slider/thumb/' . $slider ['image'], array (
					'title' => $slider ['title'] 
			) );
		}
		?>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="wrapper">
		<div class="content">
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>

	<div class="footer-wrapper">
		<div class="footer">
			<table style="width: 100%">
				<tr>
					<td style="width: 30%; vertical-align: top" class="contact">
						<table style="width: 100%">
							<tr class="tr-title">
								<td>
									<table style="width: 100%">
										<tr class="tr-title">
											<td class="box-title">با ما در ارتباط باشید</td>
											<td></td>
											<td class="box-bg"></td>
											<td></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<div class="footer-box">
										<table>
											<tr>
												<td colspan="2">
													<?php if(!empty($contact['email'])) { ?>
													<div class="field">
														<?php echo $this->Html->image('fmail.png'); ?>
														پست الکترونیک
													</div>
													<div class="value">
														<?= $contact['email']?>
													</div> <?php } ?>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<?php if(!empty($contact['address'])) { ?>
													<div class="field">
														<?php echo $this->Html->image('fadd.png'); ?>
														آدرس پستی
													</div>
													<div class="value">
														<?= $contact['address']?>
													</div> <?php } ?>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<?php if(!empty($contact['postal_code'])) { ?>
													<div class="field">
														<?php echo $this->Html->image('fcod.png'); ?>
														کد پستی
													</div>
													<div class="value">
														<?= $contact['postal_code']?>
													</div> <?php } ?>
												</td>
											</tr>
											<tr>
												<td>
													<?php if(!empty($contact['Tels'])) { ?>
													<div class="field">
														<?php echo $this->Html->image('ftel.png'); ?>
														تلفن های تماس
													</div> <?php foreach ($contact['Tels'] as $Tel){ ?>
													<div class="tel">
														<?= $Tel['telephone']?>
													</div> <?php } ?> <?php } ?>
												</td>
												<td align="left" valign="bottom">
													<?php if(!empty($clinic['footerImage'])){ echo $this->Html->image('clinic/footer/'.$clinic['footerImage'],array('class'=>'footer-logo')); ?>
													<?php } ?>
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td style="width: 70%; vertical-align: top" class="map">
						<table style="width: 100%">
							<tr class="tr-title">
								<td>
									<table style="width: 100%">
										<tr class="tr-title">
											<td class="box-title">موقعیت مکانی</td>
											<td></td>
											<td class="box-bg"></td>
											<td></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<div style="background: #ffffcc" class="footer-box"
										id="map-canvas"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center">
						<?php echo $this->Html->image('sepbotline.png',array('class'=>'footer-line')); ?>
					</td>
				
				
				<tr>
			
			</table>
			<div class="contactUs">
				<div style="float: right">
					<ul
						class="<?= empty($this -> action) ? 'home' : $this -> action; ?>">
						<li class="home"><a href="<?= $cdnroot ?>">صفحه اصلی</a></li>
						<?php if(empty($index)){ ?>
						<li>/</li>
						<li class="biography"><a href="<?= $cdnroot ?>biography">بیوگرافی پزشکان</a></li>
						<li>/</li>
						<li class="queue"><a href="<?= $cdnroot ?>queue">نوبت دهی</a></li>
						<li>/</li>
						<li class="news"><a href="<?= $cdnroot ?>news">اخبار</a></li>
						<li>/</li>
						<li class="gallery"><a href="<?= $cdnroot ?>gallery">گالری تصاویر</a></li>
						<li>/</li>
						<li class="about"><a href="<?= $cdnroot ?>about">درباره ما</a></li>
						<?php } ?>
					</ul>
				</div>
				<div style="float: left; direction: ltr">
					copyright © <span style="font-family: tahoma">2015</span> <span
						style="color: #fff">A.Atoofi</span>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->Html->image('backtop.png',array('class'=>'back-to-top','id'=>'back-to-top','style'=>'display:none')); ?>
</body>
</html>
