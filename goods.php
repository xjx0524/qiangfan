<?php

class Goods {

	private static $goodsUrl = 'http://buy.ubox.cn/index/vminfo/0231003/1?tabid=2&page=1';
	private static $buyUrl = 'http://buy.ubox.cn/index/buy';

	private static function baixing() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, Goods::$goodsUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = json_decode(curl_exec($ch));
		curl_close($ch);
		return $res;
	}

	public static function getGoods() {
		$res = Goods::baixing();

		$list = array();
		if ($res->head->message == '请求成功') {
			foreach ($res->data->list as $l) {
				$pl = $l->productList;
				$list = array_merge($list, $pl);
			}
		}
		return $list;
	}

	public static function buy() {
		if (!Goods::onSell()) return "未开�;
		$couponId = '4084203';
		$vmCode = '0231003';
		$vTypeId = '1';
		$sellerId = 2;
		$tabCategoryId = 2;
		$willList = array();
		$productList = Goods::getGoods();

		$options = array(
			CURLOPT_URL => Goods::$buyUrl,
			CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_COOKIE => "SHOPPINGCART=cyd9X7Lea8OWke226nExPQER7ODeqAhP8HBwPEWSJlOwA99SzCKVB3z61Nag5-KyjLFHXuV0KwGPPlO5DszBVnu3wFwf_pTIvQZzjwdMjXXBbpEs0V9y4FLRnUf07fgaf5PLg5j3jSW4W_shK-oNPmb9cZk17SmVgPis9QT63aY; PHPSESSID=55vl4igjgvfd09v55i6qrff137; Hm_lvt_f8cb16b7a685768c976896e565ad32ae=1409629722,1409716029,1409802631,1409888102; Hm_lpvt_f8cb16b7a685768c976896e565ad32ae=1409888105"
		);
		foreach ($willList as $productId) {
			if ($p = Goods::get($productList, $productId)) {
				if ($p->num == 0) continue;
				$data = json_encode(array(
					"sellerId" => $sellerId,
					"couponId" => $couponId,
					"productId" => $productId,
					"vmCode" => $vmCode,
					"vTypeId" => $vTypeId,
					"tabCategoryId" => $tabCategoryId
				));
				$options[CURLOPT_POSTFIELDS] = $data;
				$ch = curl_init();
				curl_setopt_array($ch, $options);
				$response = curl_exec($ch);
				if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
					$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
					$header = substr($response, 0, $headerSize);
					$body = substr($response, $headerSize);
				}
				curl_close($ch);
				var_dump($p);
				return $body;
			}
		}
		return "没有你想要的或者都卖完�;
	}

	private static function get($productList, $productId) {
		foreach ($productList as $p) {
			if ($p->productId == $productId)
				return $p;
		}
		return null;
	}

	public static function onSell() {
		$res = Goods::baixing();
		if ($res->data->sellStatus == 1) return true;
		return false;
	}
}