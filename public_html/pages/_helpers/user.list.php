<li class="user">
	<?php if($this->hasPhoto()) { ?>
		<img src="<?=$this->photo('thumb')?>" />
	<?php } ?>
	<?=$data['username']?>
</li>