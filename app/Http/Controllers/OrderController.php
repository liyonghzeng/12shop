<?php

namespace App\Http\Controllers;

use App\Goods;
use  App\Cartmodel;
use   App\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    //
    public function  index(Request $request)
    {
        $user_id=Auth::id();
        if($user_id ==''){
            $json_date=[
                'error'=>55,
                'msg'=>'请登录后操作'
            ];
            return $json_date;
        }
       $is_num= $request->input('is_num');
      $num=rtrim($is_num,',');
      $sun =  explode(',',$num);

        $shangjia_name = '';

      foreach ($sun as $k=>$v)
      {
          $where = [
            'user_id'=>$user_id,
            'cart_id'=>$v
          ];
          $cc=Cartmodel::where($where)->first();
          $shangjia_name .=$cc['goods_business'].',';
      }die;
        $goods_business=rtrim($shangjia_name,',');
        $business =  explode(',',$goods_business);
        foreach ($business as $k=>$v)
        {
            $where = [
                'user_id'=>$user_id,
                'goods_business'=>$v
            ];
            $cc=Cartmodel::where($where)->first();
            //用一个不同商品
            $goods_num=$this->selectgoods($cc['goods_business']);
           $cart = $this->selectgoodsnum($cc['goods_business']);
            $order = [

            ];
            OrderModel::where()->insert();
            $shangjia_name .=$cc['goods_business'].',';
        }
    }
    //用同一家 商品id
    public function selectgoods($goods_business)
    {
        $cc=Cartmodel::where(['goods_business'=>$goods_business])->get();
        $name = '';
        foreach ($cc as $k=>$v)
        {
            $name .= $cc->goods_id.',';
        }
        $goods_num=rtrim($name,',');
        return $goods_num;
    }

    //购买数量

    public function selectgoodsnum($goods_business)
    {
        $cc=Cartmodel::where(['goods_business'=>$goods_business])->get();
        $name = '';
        foreach ($cc as $k=>$v)
        {
            $name .= $cc->cart_num.',';
        }
        $cart_num=rtrim($name,',');
        return $cart_num;
    }

    //同一家商品 总价格
    public function pricegoods($goods_business)
    {
        $cc=Cartmodel::where(['goods_business'=>$goods_business])->get();
        $name = '';
        foreach ($cc as $k=>$v)
        {
            $name .= $cc->cart_num.',';
        }
        $cart_num=rtrim($name,',');
        return $cart_num;
    }


    ///**************************
    //购买一件
    //添加订单
    public function solegoods(Request $request)
    {
        if(Auth::id()=="")
        {
            return false;
        }

        $goods_id=  $request->input('goods_id');

        $uniq_where = [
            'goods_id'=>$goods_id,
            'user_id'=>Auth::id()
        ];
       $uniq= OrderModel::where($uniq_where)->first();
       if($uniq)
       {
            echo  '你之前有这个订单，请完成该等单后继续购买';

           //添加条件
           $add_where = [
               'order_dd' => md5("lyz" . mt_rand(1, 9999) . time()),
               'goods_id'=>$goods_id,
               "order_num" => 1,
               'goods_name' => $uniq['goods_name'],
               'goods_repertory' => $uniq['goods_repertory'],
               'goods_business' => $uniq['goods_business'],
               'user_id' => Auth::id(),
               'order_time' => time()
           ];
           $dc = OrderModel::insert($add_where);

           //支付
           $appid = '2016092500595896';
           $ali_gateway = 'https://openapi.alipaydev.com/gateway.do';
           //请求参数;
           $biz_cont = [
               'subject' => '测试订单' . mt_rand(11111, 99999) . time(),
               'out_trade_no' => '1810_' . mt_rand(11111, 99999) . time(),
               'total_amount' => $uniq['goods_price'],
               'product_code' => 'QUICK_WAP_WAY',
           ];
           //公共参数
           $data = [
               'app_id' => $appid,
               'method' => 'alipay.trade.wap.pay',
               'charset' => 'utf-8',
               'sign_type' => 'RSA2',
               'timestamp' => date('Y-m-d H:i:s'),
               'version' => '1.0',
               'biz_content' => json_encode($biz_cont)
           ];
           // 1 排序参数
           ksort($data);
           // 2 拼接带签名字符串
           $str0 = "";
           foreach ($data as $k => $v) {
               $str0 .= $k . '=' . $v . '&';
           }
           $str = rtrim($str0, '&');
           // 3 私钥签名
           $priv = openssl_get_privatekey("file://" . storage_path('key/private.pem'));
           openssl_sign($str, $signature, $priv, OPENSSL_ALGO_SHA256);
           $data['sign'] = base64_encode($signature);
           // 4 urlencode
           $param_str = '?';
           foreach ($data as $k => $v) {
               $param_str .= $k . '=' . urlencode($v) . '&';
           }
           $param = rtrim($param_str, '&');
           $url = $ali_gateway . $param;
           //发送GET请求
           header("refresh:3;url=$url");
       }else {
           $where = [
               'goods_id' => $goods_id,
           ];
           $cc = Goods::where($where)->first();
           //添加条件
           $add_where = [
               'order_dd' => md5("lyz" . mt_rand(1, 9999) . time()),
               'goods_id'=>$goods_id,
               "order_num" => 1,
               'goods_name' => $cc['goods_name'],
               'goods_repertory' => $cc['goods_repertory'],
               'goods_business' => $cc['goods_business'],
               'user_id' => Auth::id(),
               'order_time' => time()
           ];
           $dc = OrderModel::insert($add_where);

           //支付
           $appid = '2016092500595896';
           $ali_gateway = 'https://openapi.alipaydev.com/gateway.do';
           //请求参数;
           $biz_cont = [
               'subject' => '测试订单' . mt_rand(11111, 99999) . time(),
               'out_trade_no' => '1810_' . mt_rand(11111, 99999) . time(),
               'total_amount' => $cc['goods_price'],
               'product_code' => 'QUICK_WAP_WAY',
           ];
           //公共参数
           $data = [
               'app_id' => $appid,
               'method' => 'alipay.trade.wap.pay',
               'charset' => 'utf-8',
               'sign_type' => 'RSA2',
               'timestamp' => date('Y-m-d H:i:s'),
               'version' => '1.0',
               'biz_content' => json_encode($biz_cont)
           ];
           // 1 排序参数
           ksort($data);
           // 2 拼接带签名字符串
           $str0 = "";
           foreach ($data as $k => $v) {
               $str0 .= $k . '=' . $v . '&';
           }
           $str = rtrim($str0, '&');
           // 3 私钥签名
           $priv = openssl_get_privatekey("file://" . storage_path('key/private.pem'));
           openssl_sign($str, $signature, $priv, OPENSSL_ALGO_SHA256);
           $data['sign'] = base64_encode($signature);
           // 4 urlencode
           $param_str = '?';
           foreach ($data as $k => $v) {
               $param_str .= $k . '=' . urlencode($v) . '&';
           }
           $param = rtrim($param_str, '&');
           $url = $ali_gateway . $param;
           //发送GET请求
           header("Location:" . $url);
       }
    }
}
