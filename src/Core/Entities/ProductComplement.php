<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class ProductComplement extends EloquentBaseModel{

	protected $table = 'cms_produtos';

	public $timestamps = false;

	protected $fillable = array('conteudo_id', 'codigo', 'download', 'download_limit', 'estoque', 'dimensoes', 'peso', 'valor_base');




} 