<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class Contentmeta extends EloquentBaseModel{


	protected $table = 'cms_conteudometas';

	public $timestamps = false;

	protected $fillable = array('conteudo_id', 'meta_key', 'meta_type', 'meta_value', 'ordem');


	/**
	 * A meta belongs to a Content
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function content()
	{
		return $this->belongsTo('Src\Core\Entities\Content', 'conteudo_id', 'id');
	}

} 