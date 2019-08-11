<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cartmodel extends Model
{
    //
    protected  $primaryKey ='cart_id';
    protected  $table ='goods_cart';
    public   $timestamps = false;
}
