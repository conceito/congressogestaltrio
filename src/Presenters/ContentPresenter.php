<?php namespace Src\Presenters;

use Src\Core\Support\Arr;
use Src\Core\Entities\File;

class ContentPresenter extends Presenter
{

	public function titulo()
	{
		return $this->entity->titulo . " - presented";
	}


	public function gallery()
	{
		$ids = explode('|', $this->entity->galeria);

		$images = File::whereIn('id', $ids)->get();

		$images->each(function ($item) use ($ids)
		{
			$item->order = $this->get_index_by_value($ids, $item->id);
		});

		return Arr::sort($images, function ($i)
		{
			return $i['order'];
		});
	}


	/**
	 * Percorre um array até encontrar o valor desejado, então retorna o índice.
	 * @param type $ids
	 * @param type $value
	 * @return boolean|int
	 */
	private function get_index_by_value($ids, $value)
	{

		foreach ($ids as $index => $id)
		{
			if ($id == $value)
			{
				return $index;
			}
		}

		return false;
	}

} 