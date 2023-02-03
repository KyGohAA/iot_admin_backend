<?php

namespace App;

use Auth;
use App\Company;

class UserAssign extends ExtendModel
{
    protected $table = 'user_assigns';
    public $timestamps = false;
    protected $listing_except_columns = [];

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of getter and setter
    |--------------------------------------------------------------------------
    |
    */
    public static function get_model_by_user_id($id)
    {
        return static::where('user_id' , '=' , $id)
                ->where('leaf_group_id' , '=' , Company::get_group_id())
                ->first();
    }    

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function user_group()
    {
        return $this->belongsTo('App\UserGroup', 'user_group_id');
    }

    public static function get_model_by_user_and_user_group_id($user_id,$user_group_id)
    {
        return static::where('user_id','=',$user_id)
                ->where('leaf_group_id','=',Company::get_group_id())
                ->where('user_group_id','=' , $user_group_id)
                ->first();
    }

    public static function groupCombobox($leaf_group_id=null)
    {
        $return = array();
        $userAssign = static::where('user_id','=',Auth::user()->id)
                        ->groupby('leaf_group_id')
                        ->distinct()
                        ->get()
                        ->toArray();
        if(count($userAssign) == 0){ return $return;}
        $leafGroupIds = array_column($userAssign,'leaf_group_id');
        //dd($leafGroupIds);
        //dd(Company::get_group_id());
        $companies = Company::getByLeafGroupId($leafGroupIds)->toArray();
        foreach($companies as $row)
        {
            $return[$row['leaf_group_id']] = $row['name'];
        }
      
      
        return $return;
    
    }

    public static function getByUserGroupIdAndNotUserAssignId($leaf_group_id,$user_group_id)
    {
        return static::where('leaf_group_id','=',$leaf_group_id)
                ->where('user_group_id','!=' , $user_group_id)
                ->select('user_id')
                ->get();
    }

    public static function getByUserGroupId($user_group_id)
    {
        return static::where('leaf_group_id','=',Company::get_group_id())
                ->where('user_group_id','=' , $user_group_id)
                ->select('user_id')
                ->get();
    }

    public static function getByUserId($user_id)
    {
        return static::where('leaf_group_id','=',Company::get_group_id())
                ->where('user_id','=' , $user_id)
                ->get();
    }

    public static function saveOrUpdateUserAssign($input){
            
            $userAssign = static::getByUserGroupId($input['user_group_id'])->toArray();
            //dd($input);
            //echo 'L g :'.Company::get_group_id().'<br>';
            foreach($input['user_list'] as $userId)
            {
                if(in_array($userId,array_column($userAssign,'user_id')))
                {
                    //echo $userId.'<br>';
                }else{
                    //echo 'New :'.$userId.'<br>';
                    $model = new UserAssign();
                    $model['leaf_group_id'] = Company::get_group_id();
                    $model['user_id'] = $userId;
                    $model['user_group_id'] = $input['user_group_id'];
                    $model->save();

                }
            }
            
            

    }
}
