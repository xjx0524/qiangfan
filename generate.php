<?php
header("Content-type: text/html; charset=utf-8");
require_once('goods.php');

$list = Goods::getGoods();
?>
<div id="list">[]</div>
<ul>
<?php
foreach ($list as $v) {
	?>
	<li>
		<label><?=$v->fullName ?></label>
		<img data-pid=<?=$v->productId ?> src="<?=$v->picMap[0] ?>"/>
	</li>
	<?php
}
?>
</ul>
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/generate.js"></script>