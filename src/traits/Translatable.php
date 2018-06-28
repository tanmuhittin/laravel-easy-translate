<?php
namespace TanMuhittin\LaraTranslate\Traits;

use App\Entities\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;


trait Translatable
{
    public $locale;
    //public $translatable_columns;

    public function __construct()
    {
        $this->locale = App::getLocale();
        array_push($this->appends,"translated");
        array_push($this->appends,"translatable");
        if(isset($this->translatable_columns) && count($this->translatable_columns)>0){

        }
        elseif(config('database.connections.'.config('database.default').'.driver') == 'mysql'){
            $this->translatable_columns = [];
            foreach(DB::select('describe '.$this->getTable()) as $column){
                if(substr($column->Type,0,7)==='varchar' || substr($column->Type,0,4)==='text') {
                    if(substr($column->Field,-4)==='type'){
                        continue;
                    }
                    $this->translatable_columns[] = $column->Field;
                }
            }
        }
        static::saving(function ($model) {
            if(isset($model->laratrans)){
                foreach ((array) $model->trans as $lang => $fields){
                    foreach ($fields as $column => $translation){
                        if(isset($translation["id"])){
                            $t=Translation::find($translation["id"]);
                        }else{
                            $t = new Translation;
                            $t->translatable_type = get_class($model);
                            $t->translatable_id = $model->id;
                        }
                        $t->value = $translation["value"];
                        $t->language = $lang;
                        $t->column = $column;
                        $t->save();
                    }
                }
                unset($model->trans);
            }
        });
        static::deleting(function ($model){
            $model->translations()->delete();
        });
    }

    public function getTranslatedAttribute()
    {
        return $this->getTranslations();
    }

    public function getTranslatableAttribute()
    {
        $translations = [];
        foreach (Config::get('app.locales') as $l){
            $translations[$l] = new \StdClass;
            foreach ($this->translatable_columns as $translatable_col){
                $translation = $this->getTranslation($translatable_col,$l);
                if($translation){
                    $translations[$l]->{$translatable_col} = $translation;
                }else{
                    $not_translated=new\stdClass();
                    $not_translated->value = $this->{$translatable_col};
                    $translations[$l]->{$translatable_col} = $not_translated;
                }
            }
        }
        return $translations;
    }

    public function getTranslations(){
        $translations = new \StdClass;
        if($this->locale !== 'en'){
            foreach ($this->translatable_columns as $translatable_col){
                $translation = $this->getTranslation($translatable_col,$this->locale);
                if($translation){
                    $translations->{$translatable_col} = $translation->value;
                }else{
                    $translations->{$translatable_col} = $this->{$translatable_col};
                }
            }
        }
        return $translations;
    }

    public function getTranslation($translatable_col,$l)
    {
        $query=$this->translations()->where('language',$l)->where('column',$translatable_col);
        if(!$query->exists())
            return false;
        return $query->first();
    }

    public function translations()
    {
        return $this->morphMany(\TanMuhittin\LaraTranslate\Entities\Translation::class, 'translatable');
    }

}