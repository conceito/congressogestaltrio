<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class Inscription  extends EloquentBaseModel{


	protected $table = 'cms_inscritos';

	public $timestamps = false;

	protected $fillable = array('conteudo_id', 'data', 'hora', 'comentario', 'ip', 'user_id', 'rel', 'status');

	protected $dates = array('data');


	/**
	 * A inscription belongs to a Content
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function content()
	{
		return $this->belongsTo('Src\Core\Entities\Content', 'conteudo_id', 'id');
	}

	/**
	 * A inscription belongs to a User
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('Src\Core\Entities\User', 'user_id', 'id');
	}


	/**
	 * A inscription could has many sub inscriptions
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subInscriptions()
	{
		return $this->hasMany('Src\Core\Entities\Inscription', 'rel', 'id');
	}

	/**
	 * A inscription could has a main inscription
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function mainInscription()
	{
		return $this->belongsTo('Src\Core\Entities\Inscription', 'rel', 'id');
	}
} 