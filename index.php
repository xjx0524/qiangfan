<?php
header("Content-type: text/html; charset=utf-8");
require_once('goods.php');

var_dump(Goods::buy());
//var_dump(Goods::onSell());
//var_dump(Goods::getGoods());
//foreach (Goods::getGoods() as $value) {
//	$pic = $value->picMap[0];
//	echo "<p>
//	<img src='$pic'/>
//	$value->fullName
//	</p>";
//}