<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Goods;
use App\Cartmodel;
use  Illuminate\Support\Facades\Auth;

class HomeListController extends Controller
{
    //商品展示页面
    public function index()
    {
        $where = [
            'goods_hot'=>1
        ];
        $goods_all =Goods::where($where)->get();
        return view('reception/index',['goods_all'=>$goods_all]);
    }

    //商品展示
        public function  goodslist(Request $request)
        {
            $goods_id=$request->input('goods');
            $goodsId=intval($goods_id);
            $where = [
                'goods_id'=>$goodsId
            ];
           $cart_list = Goods::where($where)->first();

           return  view('reception/goodslsit',['cart_list'=>$cart_list]);
        }
    //cart
    public function cartlist(Request $request)
    {
        if(Auth::id() ==''){
            $json_date=[
                'error'=>55,
                'msg'=>'请登录后操作'
            ];
           header('refresh:3;url="/login"');
        }
        $goods_id=$request->input('goods_id');

        $cart=intval($goods_id);
        $where = [
            'goods_id'=>$cart
        ];

        $cart_list = Goods::where($where)->first();
        //判断商品是否存在
        if($cart_list['goods_id'])
        {
            $cart_select= Cartmodel::where($where)->first();
            if($cart_select){
                $cart_where = [
                    'cart_num' => $cart_select['cart_num']+1
                ];
                $cart_Add= Cartmodel::where($where)->update($cart_where);
            }else{
                $cart_where = [
                    'goods_name'=>$cart_list['goods_name'],
                    'goods_discount'=>$cart_list['goods_name'],
                    'goods_desc'=>$cart_list['goods_desc'],
                    'goods_img'=>$cart_list['goods_img'],
                    'goods_business'=>$cart_list['goods_business'],
                    'goods_id'=>$cart,
                    'cart_time'=>time(),
                    'cart_num'=>1,
                    'user_id'=>Auth::id()
                ];
                $cart_Add= Cartmodel::insert($cart_where);
            }
            if($cart_Add)
            {
                $json_date=[
                    'error'=>0,
                    'msg'=>'添加购物车成功'
                ];
            }else{
                $json_date=[
                    'error'=>1,
                    'msg'=>'添加购物车失败'
                ];

            }

        }else{
            $json_date=[
                'error'=>3,
                'msg'=>'商品不存在,添加购物车失败'
            ];

        }
        return json_encode($json_date);
    }

    //cart 展示
    public function listCart()
    {
        if(Auth::id() ==''){
            $json_date=[
                'error'=>55,
                'msg'=>'请登录后操作'
            ];
            echo json_encode($json_date);
            header('refresh:3;url="/login"');
        }
        $where = [
            'user_id'=>auth::id(),
            'cart_status'=>1
        ];
       $cc= Cartmodel::where($where)->get();
       return view('cart/cartlist',['cc'=>$cc]);
    }

    //删除
    public function delcart(Request $request)
    {
        if(Auth::id() ==''){
            $json_date=[
                'error'=>55,
                'msg'=>'请登录后操作'
            ];
            echo json_encode($json_date);
            header('refresh:3;url="/login"');
        }
        $cartid= $request->input('cartid');
        $where = [
            'user_id'=>Auth::id(),
            'cart_id'=>intval($cartid)
        ];
        $update_data = [
            'cart_status'=>2
        ];
        $cart_update= Cartmodel::where($where)->update($update_data);
        if($cart_update)
        {
            $json_date=[
                'error'=>0,
                'msg'=>'购物车清除成功'
            ];

        }else{
            $json_date=[
                'error'=>1,
                'msg'=>'购物车清除失败'
            ];
        }
        return json_encode($json_date);
    }

    //购物车数量
    public function  cartnum(Request $request)
    {
        if(Auth::id() ==''){
            $json_date=[
                'error'=>55,
                'msg'=>'请登录后操作'
            ];
            echo json_encode($json_date);
            header('refresh:3;url="/login"');
        }
       $num= $request->input('cartnum');
       $cartid= $request->input('cartid');
       $cartint=intval($num);

        $where = [
            'user_id'=>Auth::id(),
            'cart_status'=>1,
            'cart_id'=>$cartid,
        ];
        $cc= Cartmodel::where($where)->first();
        if($cc['goods_repertory'] > $cartint){
            $update_data = [
                'cart_num'=>$cartint
            ];
            $cart_update= Cartmodel::where($where)->update($update_data);
            if($cart_update)
            {
                $json_date=[
                    'error'=>0,
                    'msg'=>'购物车清除成功'
                ];

            }else{
                $json_date=[
                    'error'=>1,
                    'msg'=>'购物车清除失败'
                ];
            }
        }else{
            $json_date=[
                'error'=>999,
                'msg'=>'数量太大'
            ];
        }

        return $json_date;
    }
}
