<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class InvoiceProduct extends EloquentBaseModel{


	protected $table = 'cms_extrat_produtos';

	public $timestamps = false;

	protected $fillable = array('extrato_id', 'conteudo_id', 'conteudo_titulo', 'quantidade', 'valor', 'opcoes', 'subtotal', 'tipo');

	protected $dates = array('data', 'dt_entrega');


	/**
	 * The Product belongs to a invoice
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function invoice()
	{
		return $this->belongsTo('Src\Core\Entities\Invoice', 'extrato_id', 'id');
	}


	/**
	 * The product object of product reference of an Invoice
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product()
	{
		return $this->belongsTo('Src\Core\Entities\Content', 'conteudo_id', 'id');
	}
} 