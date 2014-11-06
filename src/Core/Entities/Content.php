<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class Content extends EloquentBaseModel {



	protected $table = 'cms_conteudo';

	public $timestamps = false;

	protected $fillable = array('id', 'ordem', 'prioridade', 'dt_ini', 'dt_fim', 'hr_ini', 'hr_fim',
		'nick', 'full_uri', 'titulo', 'resumo', 'txt', 'txtmulti', 'tags', 'img', 'galeria',
		'rel', 'autor', 'destaque', 'lang', 'modulo_id', 'grupo', 'tipo', 'atualizado',
		'semana', 'extra', 'show', 'metadados', 'scripts', 'status');



	protected $dates = array('dt_ini', 'dt_fim', 'atualizado');

//	protected $appends = array('grupo_nome');

	protected $presenter = 'Src\Presenters\ContentPresenter';

	/**
	 * A content has many metas
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function metas()
	{
		return $this->hasMany('Src\Core\Entities\Contentmeta', 'conteudo_id', 'id');
	}
}

