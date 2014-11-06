<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class User extends EloquentBaseModel{

	protected $table = 'cms_usuarios';

	public $timestamps = false;

	protected $fillable = array('nome', 'razao', 'fantasia', 'profissao', 'atividade', 'cargo', 'email',
		'email2', 'sexo', 'nasc', 'cep', 'logradouro', 'num', 'compl', 'cidade', 'bairro', 'uf', 'tel1',
		'tel2', 'foto', 'dt_ini', 'dt_fim', 'rg', 'cpf', 'cnpj', 'insc_estadual', 'insc_municipal',
		'grupo', 'filtro', 'news', 'senha', 'erros', 'lang', 'obs', 'ordem', 'visitas', 'extra',
		'regiao_entrega', 'status');

	protected $dates = array('dt_ini', 'dt_fim');

	protected $appends = array('tipo');

	protected $presenter = 'Src\Presenters\UserPresenter';




	/**
	 * A user has many metas
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function metas()
	{
		return $this->hasMany('Src\Core\Entities\Usermeta', 'usuario_id', 'id');
	}

	/**
	 * A user has many invoices
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function invoices()
	{
		return $this->hasMany('Src\Core\Entities\Invoice', 'usuario_id', 'id');
	}

	/**
	 * A user has many inscriptions
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function inscriptions()
	{
		return $this->hasMany('Src\Core\Entities\Inscription', 'user_id', 'id');
	}


	/**
	 * Return if is 'pf' or 'pj'
	 *
	 * @return string
	 */
	public function getTipoAttribute()
	{
		return (strlen($this->attributes['cnpj']) < 4) ? 'pf' : 'pj';
	}



} 