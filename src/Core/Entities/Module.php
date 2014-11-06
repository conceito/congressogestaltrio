<?php namespace Src\Core\Entities;


use Src\EloquentBaseModel;

class Module extends EloquentBaseModel{

	protected $table = 'cms_modulos';

	public $timestamps = false;

	protected $fillable = array('ordem', 'label', 'uri', 'front_uri', 'tabela', 'tipo', 'grupo', 'acao', 'ico', 'destaques', 'comments', 'ordenavel', 'inscricao', 'multicontent', 'rel', 'pasta_img', 'pasta_arq', 'pasta_ajuda', 'extra', 'status');

} 