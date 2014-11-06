<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class State  extends EloquentBaseModel{

	protected $table = 'opt_estado';

	public $timestamps = false;

	protected $fillable = array('uf', 'nome');

	/**
	 * A State has many cities
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function cities()
	{
		return $this->hasMany('Src\Core\Entities\City', 'uf', 'uf');
	}

} 