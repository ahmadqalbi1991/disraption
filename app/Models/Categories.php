<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use SoftDeletes;

    protected $table = "category";
    protected $primaryKey = "id";
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $guarded = [];
    //public $appends = ['product_count'];

    // public function getProductCountAttribute(){
    //     return "0";
    // }
    
    public function getImageAttribute($image){

        return get_uploaded_image_url( $image, 'category_image_upload_dir', 'placeholder.png' );
    } 

    public function getBannerImageAttribute($banner_image){

        return get_uploaded_image_url( $banner_image, 'category_image_upload_dir', 'placeholder.png' );
    } 

    public function children() {
        return $this->hasMany('App\Models\Categories', 'parent_id', 'id'); 
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


    public static function GetFormatedCategories()
    {

        // -------- Get the addon categories -----------
        $categories = Categories::select('id', 'name')->orderBy('sort_order', 'asc')->where(['deleted' => 0, 'active' => 1, 'parent_id' => 0])->get();

        // Loop through the categories and get the children
        foreach ($categories as $key => $category) {
            $categories[$key]->sub = Categories::select('id', 'name')->orderBy('sort_order', 'asc')->where(['deleted' => 0, 'active' => 1, 'parent_id' => $category->id])->get();
        }
        // ----------------------------------------------

        return $categories;
    }


}
