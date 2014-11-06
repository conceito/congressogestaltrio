<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class Invoice extends EloquentBaseModel {

	protected $table = 'cms_extratos';

	public $timestamps = false;

	protected $fillable = array('modulo_id', 'usuario_id', 'inscricao_id', 'fatura', 'metodo', 'tipo_pagamento', 'transacao_id', 'parcelas', 'valor_total', 'descontos', 'cupons', 'tipo_frete', 'valor_frete', 'data', 'hora', 'anotacao', 'comprovante', 'dt_entrega', 'status');

	protected $dates = array('data', 'dt_entrega');


	/**
	 * A invoice belongs to a User
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('Src\Core\Entities\User', 'usuario_id', 'id');
	}


	/**
	 * A invoice could belongs to a Inscription
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function inscription()
	{
		return $this->belongsTo('Src\Core\Entities\Inscription', 'inscricao_id', 'id');
	}


	/**
	 * A invoice belongs to a Module
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function module()
	{
		return $this->belongsTo('Src\Core\Entities\Module', 'modulo_id', 'id');
	}


	/**
	 * The products of this invoice
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function products()
	{
		return $this->hasMany('Src\Core\Entities\InvoiceProduct', 'extrato_id', 'id');
	}

	/**
	 * The history of this Invoice
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function history()
	{
		return $this->hasMany('Src\Core\Entities\InvoiceHistory', 'extrato_id', 'id');
	}



} 