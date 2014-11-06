<?php namespace Src\Core\Entities;

class Tag extends Content
{


	/**
	 * Tags has tipo == 'tag'
	 *
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function newQuery()
	{
		$query = parent::newQuery();

		$query->where('tipo', '=', 'tag');

		return $query;
	}

} 