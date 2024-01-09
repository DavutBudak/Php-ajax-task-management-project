<?php if ($total_items > $this->items_per_page) { ?>
	<nav>
		<ul class="pager tiva-pager">
			<?php for ($i = 1; $i <= $page; $i++) { ?>
				<li><a <?php echo $i == $cur_page ? 'class="active"' : ''; ?> href="<?php echo $relative_url . 'system/pager.php?p=' . $i . '&url=' . $list_page; ?>"><?php echo $i; ?></a></li>
			<?php } ?>
		</ul>
	</nav>
<?php } ?>