<head>
	<meta charset="utf-8">
</head>
<form action="import.php" method="post">
	<textarea name="data"></textarea>
	<input type="text" name="date"/>
	<input type="submit" name="submit" value="submit"/>
</form>
<?php
exit;
require_once('PDOWrapper.php');
	if (isset($_POST['submit'])) {
		$res = json_decode($_POST['data']);
		$date = $_POST['date'];

		foreach ($res->data->list as $l) {
			foreach ($l->productList as $product) {
				if (PDOWrapper::instance()->select('q_product', array('productId' => $product->productId))) {
					$result = PDOWrapper::instance()->update('q_product', array('lastTime' => $_POST['date']),
						array('productId' => $product->productId));
					if ($result) {
						echo "<div>";
						echo "update ".$product->productId;
						echo "</div>";
					}
				} else {
					$p = array();
					$p['productId'] = $product->productId;
					$p['shortName'] = $product->shortName;
					$p['providerName'] = $product->providerName;
					$p['picMap'] = $product->picMap[0];
					$p['lastTime'] = $date;
					$id = PDOWrapper::instance()->insert('q_product', $p);
					echo "<div>";
					echo "insert $id";
					echo "</div>";
				}
				$p = array();
				$p['productId'] = $product->productId;
				$p['date'] = $date;
				PDOWrapper::instance()->insert('q_product_meta', $p);
			}
		}
	}