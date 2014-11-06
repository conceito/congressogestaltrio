<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class Usermeta extends EloquentBaseModel{


	protected $table = 'cms_usuariometas';

	public $timestamps = false;

	protected $fillable = array('usuario_id', 'meta_key', 'meta_type', 'meta_value', 'ordem');


	/**
	 * A meta belongs to a Content
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('Src\Core\Entities\User', 'usuario_id', 'id');
	}

} 