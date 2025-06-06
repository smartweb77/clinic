<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\Traits\ActionLog;

class History extends Model 
{
    use ActionLog;

    protected $fillable = ['image'];
    protected $table = 'histories';
    private static $current_class = __CLASS__; 
    private static $translates_class = 'App\Models\HistoryTranslate';
    private static $main_table = 'histories';
    private static $translates_table = 'history_translates';
    
    public static function get_required_lang()
    {
        return DB::table('configurations')->select('admin_lang')->first()->admin_lang;
    }

    public static function updateItem($request, $item) 
    {   
        $class_base_name = class_basename(self::$current_class);
        $table_columns = Schema::getColumnListing(self::$main_table);
        $request_keys = $request->except([
            '_token',
            'translates',
            'logo',
            'logo_for_admin',
            'favicon',
            'login_bg',
            'status'
        ]);
        
        foreach ($request_keys as $key => $value) 
        {
            if(in_array($key, $table_columns))
            {
                $item->$key = $value;
            }
        }
        
        if ($request->hasFile('image')) 
        {
            $destination = 'uploads/history';
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = mt_rand(11111,99999).time().'.'.$extension;
            $request->file('image')->move($destination, $fileName);
            $image_src = '/uploads/history/'.$fileName;
            $item->image = $image_src;
        }   

        if($item->update()) 
        {
            if(property_exists(__CLASS__, 'translates_table'))
            {
                $translates = $request->translates;
                $translates_table_columns = Schema::getColumnListing(self::$translates_table);

                foreach ($translates as $lang => $translation_data) 
                {
                    $item_translate = self::$translates_class::where('parent_id', $item->id)->where('lang', $lang)->first();

                    foreach($translation_data as $k => $v)
                    {
                        /*  თუ რედაქტირების შაბლონში აღწერილია ისეთი ველი, რომლის 'name' 
                         *  ატრიბუტის  შესაბამისი ველიც არ გვხვდება თარგმანების ცხრილში
                         */
                        if(!in_array($k, $translates_table_columns))
                        {
                            continue;
                        }

                        if(!$v)
                        {
                            $item_translate->$k = $translates[self::get_required_lang()][$k];
                        }
                        else
                        {
                            $item_translate->$k = $v;
                        }                   
                    }          

                    $item_translate->update();
                }
            }
            $item::storeLog($item, __CLASS__, self::$main_table, 'Update');
            return true;
        }
        return false;
    }

    public static function getItemInfo($id = 1, $local = '') 
    {
        if(property_exists(__CLASS__, 'translates_table'))
        {
            return self::$current_class::join(self::$translates_table, self::$main_table.'.id', self::$translates_table.'.parent_id')
                ->where(self::$main_table.'.id', $id)
                ->where(self::$translates_table.'.lang', $local)
                ->select(self::$main_table.'.*', 
                         self::$translates_table.'.title',
                         self::$translates_table.'.description'
                        )
                ->first();
        }
        else
        {
            return self::$current_class::where(self::$main_table.'.id', $id)->first();
        }        
    }   
}
