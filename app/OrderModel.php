<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    //
    protected  $primaryKey ='order_id';
    protected  $table ='shop_order';
    public   $timestamps = false;

}
