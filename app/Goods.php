<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    //
    protected  $primaryKey ='goods_id';
    protected  $table ='shop_goods';
    public   $timestamps = false;

}
