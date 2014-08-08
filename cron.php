<?php
require_once('PDOWrapper.php');
require_once('goods.php');

$insert = 0;
$update = 0;
$date = date("Y-m-d");

foreach (Goods::getGoods() as $product) {
	if (PDOWrapper::instance()->select('q_product', array('productId' => $product->productId))) {
		$result = PDOWrapper::instance()->update('q_product', array('lastTime' => $date),
			array('productId' => $product->productId));
		if ($result) {
			$update++;
//			echo "<div>";
//			echo "update ".$product->productId;
//			echo "</div>";
		}
	} else {
		$p = array();
		$p['productId'] = $product->productId;
		$p['shortName'] = $product->shortName;
		$p['providerName'] = $product->providerName;
		$p['picMap'] = $product->picMap[0];
		$p['lastTime'] = $date;
		$id = PDOWrapper::instance()->insert('q_product', $p);

		$insert++;
//		echo "<div>";
//		echo "insert $id";
//		echo "</div>";
	}
	$p = array();
	$p['productId'] = $product->productId;
	$p['date'] = $date;
	PDOWrapper::instance()->insert('q_product_meta', $p);
}

echo "insert: $insert\n";
echo "update: $update\n";
