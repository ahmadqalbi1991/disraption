<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppBanner extends Model
{
 
    // fillable
    protected $fillable = [
        'name',
        'banner_image',
        'active',
        'sort_order',
    ];

    // hidden
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    

    public function getBannerImageAttribute($banner_image){

        return get_uploaded_image_url( $banner_image, 'app_banners', 'placeholder.png' );
    } 


    // public function getBannerImageAttribute($value){
    //     if($value){
    //         return $this->path.$value;
    //     }else{
    //         return $this->path.'placeholder.png';
    //     }

    // }
    public static function sort_item($item=[]){
        if( !empty($item) ){
            DB::beginTransaction();
            try {
                    $i=0;
                    foreach( $item as $key ){
                        Categories::where('id', $key)
                            ->update(['sort_order' => $i]);
                        $i++;
                    }
                    DB::commit();
                return 1;
            } catch (\Exception $e) {
                DB::rollback();
                return 0;
            }
        }else{
            return 0;
        }
    }

}
