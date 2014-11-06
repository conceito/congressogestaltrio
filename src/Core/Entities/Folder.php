<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class Folder extends EloquentBaseModel {

	protected $table = 'cms_pastas';

	public $timestamps = false;

	protected $fillable = array('dt_ini', 'titulo', 'nick', 'txt', 'img', 'ordem', 'mini_w', 'mini_h', 'med_w', 'med_h', 'max_w', 'max_h', 'cor', 'tipo', 'grupo', 'destaque', 'lang', 'autor', 'status');

	protected $dates = array('dt_ini');


	/**
	 * A folder has many files
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function files()
	{
		return $this->hasMany('Src\Core\Entities\File', 'pasta', 'id');
	}
}

