<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class City  extends EloquentBaseModel{

	protected $table = 'opt_cidades';

	public $timestamps = false;

	protected $fillable = array('uf', 'nome', 'status');

	/**
	 * A City belongs to a State
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function state()
	{
		return $this->belongsTo('Src\Core\Entities\State', 'uf', 'uf');
	}
} 