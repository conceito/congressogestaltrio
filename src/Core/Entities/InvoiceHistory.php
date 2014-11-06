<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class InvoiceHistory extends EloquentBaseModel{

	protected $table = 'cms_extrat_hist';

	public $timestamps = false;

	protected $fillable = array('extrato_id', 'data', 'hora', 'anotacao', 'obs', 'status', 'notificado');

	protected $dates = array('data');


	/**
	 * This History belongs to a Invoice
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function invoice()
	{
		return $this->belongsTo('Src\Core\Entities\Invoice', 'extrato_id', 'id');
	}
} 