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
		if (!Goods::onSell()) return "未开启";
		$couponId = '3953839';
		$vmCode = '0231003';
		$vTypeId = '1';
		$sellerId = 2;
		$tabCategoryId = 2;
		$willList = [5241,5236,5238,5470,5837,7723,4935,4938,4941,5102,5124,5201,7412,7541,5595,5663,5666,3401,4423,5100,7252,7364,7366,7397];
		$productList = Goods::getGoods();

		$options = [
			CURLOPT_URL => Goods::$buyUrl,
			CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_COOKIE => "PHPSESSID=5usi2792b1hcsdcvjabbfb5356; SHOPPINGCART=cyd9X7Lea8OWke226nExPQER7ODeqAhP8HBwPEWSJlOwA99SzCKVB3z61Nag5-KyjLFHXuV0KwGPPlO5DszBVnu3wFwf_pTIvQZzjwdMjXXBbpEs0V9y4FLRnUf07fgaKaT03Ms5ZWPnNboKVhjRzD2yt-9TL4y135Uy0J1WmwM; Hm_lvt_f8cb16b7a685768c976896e565ad32ae=1405560087; Hm_lpvt_f8cb16b7a685768c976896e565ad32ae=1405562211"
		];
		foreach ($willList as $productId) {
			if ($p = Goods::get($productList, $productId)) {
				if ($p->num == 0) continue;
				$data = json_encode([
					"sellerId" => $sellerId,
					"couponId" => $couponId,
					"productId" => $productId,
					"vmCode" => $vmCode,
					"vTypeId" => $vTypeId,
					"tabCategoryId" => $tabCategoryId
				]);
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

	}

	private static function get($productList, $productId) {
		foreach ($productList as $p) {
			if ($p->productId == $productId)
				return $p;
		}
		return null;
	}

	private static function onSell() {
		$res = Goods::baixing();
		if ($res->data->sellStatus == 1) return true;
		return false;
	}
}