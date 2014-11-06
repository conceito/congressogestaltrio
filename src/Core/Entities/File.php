<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class File extends EloquentBaseModel {

	protected $table = 'cms_arquivos';

	public $timestamps = false;

	protected $fillable = array('dt_ini', 'nome', 'descricao', 'ordem', 'width', 'height', 'pos', 'img', 'ext', 'peso', 'pasta', 'rel', 'tag_opt', 'conteudo_id', 'downloaded');

	protected $dates = array('dt_ini');

	protected $presenter = 'Src\Presenters\FilePresenter';


	/**
	 * A file belongs to a Folder
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function folder()
	{
		return $this->belongsTo('Src\Core\Entities\Folder', 'pasta', 'id');
	}

	/**
	 * A file could belongs to a Content
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function content()
	{
		return $this->belongsTo('Src\Core\Entities\Content', 'conteudo_id', 'id');
	}


}

