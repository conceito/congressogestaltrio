<?php namespace Src\Product;


use Src\Entities\Content;

class Category extends Content{


	/**
	 * Products have modulo_id == 52
	 *
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function newQuery()
	{
		$query = parent::newQuery();

		$query->where('modulo_id', '=', 52)->where('grupo', '=', 0);

		return $query;
	}

	/**
	 * A Category has many Products
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function products()
	{
		return $this->hasMany('Src\Product\Product', 'grupo', 'id');
	}

} 