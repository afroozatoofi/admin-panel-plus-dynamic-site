<?php if(!empty($about)){ ?>
<div class="about">
	<table style="width: 100%">
		<tr>
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
		<table>
					<tr>
						<td class="text">
							<div>
								<?= nl2br($about) ?>
							</div>
						</td>
					</tr>
				</table>
		</tr>
	</table>
</div>
<?php } ?>