<?php if (isset($header) && $header): ?>
	<p><?= $header ?></p>
<?php endif; ?>
<?php if (isset($pairs) && is_array($pairs) && count($pairs) > 0): ?>
	<ul>
		<?php foreach ($pairs as $key => $value): ?>
			<li><strong><?= $key ?>:</strong> <?= $value ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php if (isset($footer) && $footer): ?>
	<p><?= $footer ?></p>
<?php endif; ?>
