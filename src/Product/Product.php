<?php namespace Src\Product;


use Src\Entities\Content;

class Product extends Content{

	/**
	 * Products have modulo_id == 52
	 *
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function newQuery()
	{
		$query = parent::newQuery();

		$query->where('modulo_id', '=', 52)->where('grupo', '>', 0);

		return $query;
	}


	/**
	 * A Product belongs to a Category
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category()
	{
		return $this->belongsTo('Src\Product\Category', 'grupo', 'id');
	}
} 