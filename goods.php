<?php

class Goods {

	private static $goodsUrl = 'http://buy.ubox.cn/index/vminfo/0231003/1?tabid=2&page=1';
	private static $buyUrl = 'http://buy.ubox.cn/index/buy';

	public static function getGoods() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, Goods::goodsUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = json_decode(curl_exec($ch));
		curl_close($ch);

		$list = array();
		if ($res->head->message == '请求成功') {
			foreach ($res->data->list as $l) {
				$pl = $l->productList;
				$list = array_merge($list, $pl);
			}
		}
		return $list;
	}
}