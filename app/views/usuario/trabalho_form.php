<script src="<?php echo base_url()?>libs/tiny_mce356/jquery.tinymce.js"></script>
<script src="<?php echo base_url()?>libs/jquery/jquery.charlimit.js"></script>

<div id="page" class="outra-classe">

    <h1 class="page-title">Atualização de trabalho</h1>


    <?php if ($this->msg): ?>
        <div class="alert alert-<?php echo ($this->msg_type == 'error') ? 'danger' : $this->msg_type ?>">
            <?php echo $this->msg ?>
        </div>
    <?php endif; ?>



    <form action="<?php echo site_url('usuario/post_trabalho/'.$job['id']); ?>" class="-form-horizontal -form-validate"
          method="post"
          id="frm_trabalho_update">

        <?php
        /**
         * checkbox para debugar submissão
         */
        if($this->phpsess->get('logado', 'cms')):?>
        <input type="checkbox" name="debug_mode" value="1"> debug
        <?php endif;?>

        <fieldset>
            <legend>1) Identificação</legend>

            <div class="form-group <?php err('eixo_tematico') ?>">
                <label class="control-label" for="field_eixo_tematico">Eixo temático</label>

                <select name="eixo_tematico" id="field_eixo_tematico" class="form-control" required>

                    <?php
                    foreach($temas as $t):
                    ?>
                        <option value="<?php echo $t['id']?>" <?php echo ($t['id'] == $eixo_tematico_id) ? 'selected'
	                        : ''?>><?php
	                        echo
	                        $t['title']?></option>
                    <?php
                    endforeach;
                    ?>
                </select>

                <?php echo form_error('eixo_tematico') ?>

            </div>


            <div class="form-group <?php err('modalidade') ?>">
                <label class="control-label" for="field_modalidade">Modalidade do trabalho</label>

                <select name="modalidade" id="field_modalidade" class="form-control" required>

                    <?php
                    foreach($modalidades as $m):
                        ?>
                        <option value="<?php echo $m['id']?>" <?php echo ($m['id'] == $modalidade_id) ? 'selected'
	                        : ''?>><?php echo $m['title']?></option>
                    <?php
                    endforeach;
                    ?>
                </select>

                <?php echo form_error('modalidade') ?>

            </div>


            <div class="form-group <?php err('titulo') ?>">
                <label class="control-label" for="field_titulo">Título</label>

                <input type="text" id="field_titulo" name="titulo" class="form-control basic-editor" value="<?php echo
                set_value('titulo', $job['titulo'])?>" data-editor-limit="130">
                <div id="field_titulo_countBox" class="count-box-bag">
                    <span class="count-label">limite: </span>
                    <span class="countBox">130</span>
                </div>
                <?php echo form_error('titulo') ?>

            </div>


            <div class="form-group <?php err('subtitulo') ?>">
                <label class="control-label" for="field_subtitulo">Subtítulo</label>

                <input type="text" id="field_subtitulo" name="subtitulo" class="form-control  basic-editor" value="<?php
                echo set_value('subtitulo', $job['subtitulo'])?>" data-editor-limit="130" data-editor-height="40">
                <div id="field_subtitulo_countBox" class="count-box-bag">
                    <span class="count-label">limite: </span>
                    <span class="countBox">130</span>
                </div>
                <?php echo form_error('subtitulo') ?>

            </div>


            <div class="form-group <?php err('resumo1') ?>">
                <label class="control-label" for="field_resumo">Resumo</label>

                <textarea name="resumo1" id="field_resumo" cols="30" rows="6"
                          class="form-control basic-editor" data-editor-limit="700"><?php echo set_value('resumo1',
		                $job['resumo'])
                    ?></textarea>
                <div id="field_resumo_countBox" class="count-box-bag">
                    <span class="count-label">limite: </span>
                    <span class="countBox">700</span>
                </div>

                <?php echo form_error('resumo1') ?>
            </div>

            <div class="form-group <?php err('palavras_chave') ?>">
                <label class="control-label" for="field_palavras_chave">Palavras-chave</label>

                <input type="text" id="field_palavras_chave" name="palavras_chave" class="form-control" required
                       value="<?php echo set_value('palavras_chave', $job['tags']) ?>">
                <div class="help-block">De 03 a 05 palavras, em letras minúsculas, separadas por ponto e vírgula.</div>
                <?php echo form_error('palavras_chave') ?>
            </div>

            <ul class="nav nav-tabs">
	            <?php
	            // loop through autors
	            foreach($job['autores'] as $index => $a):
		            $i = $index + 1;
	            ?>
                <li class="<?php echo ($i==1)?'active':''?>"><a href="#autor-<?php echo $i?>" data-toggle="tab" data-author-tab="<?php echo $i?>">Autor (<?php echo $i?>)
	                </a></li>
	            <?php
	            endforeach;
	            ?>


            </ul>

            <div class="tab-content">
	            <?php
	            // loop through autors
	            foreach($job['autores'] as $index => $a):
		            $i = $index + 1;
		            ?>
		            <div class="tab-pane <?php echo ($i==1)?'active':''?>" id="autor-<?php echo $i?>">

			            <div class="form-group <?php err('autor_nome_'.$i) ?>">
				            <label class="control-label" for="field_autor_nome_<?php echo $i?>">Nome do autor</label>

				            <input type="text" id="field_autor_nome_<?php echo $i?>" name="autor_nome_<?php echo $i?>" class="form-control" required
				                   value="<?php echo set_value('autor_nome_'.$i, $a['nome']) ?>">
				            <?php echo form_error('autor_nome_'.$i) ?>
			            </div>

			            <div class="form-group <?php err('curriculo_'.$i) ?>">
				            <label class="control-label" for="field_curriculo_<?php echo $i?>">Minicurrículo</label>

				            <textarea name="curriculo_<?php echo $i?>" id="field_curriculo_<?php echo $i?>" cols="30" rows="6"
				                      class="form-control basic-editor" data-editor-limit="500"><?php echo set_value
					            ('curriculo_'.$i, $a['curriculo']) ?></textarea>
				            <div id="field_curriculo_<?php echo $i?>_countBox" class="count-box-bag">
					            <span class="count-label">limite: </span>
					            <span class="countBox">500</span>
				            </div>
				            <?php echo form_error('curriculo_'.$i) ?>

			            </div>

		            </div>
	            <?php
	            endforeach;
	            ?>


            </div>
            <!-- tab-content-->





        </fieldset>


        <fieldset class="<?php err('proposta') ?>">
            <legend>2) Proposta</legend>

            <div class="form-group ">

                <textarea name="proposta" id="field_proposta" cols="30" rows="20"
                          class="form-control proposal-editor"><?php echo set_value('proposta',
		                $job['txt']) ?></textarea>

                <div id="charPropostaLimit" class="count-box-bag">
                    <span class="count-label">caracteres: </span>
                    <span class="countBox">0</span>
                </div>
                <?php echo form_error('proposta') ?>
            </div>

        </fieldset>

        <a href="#" class="btn -btn-default" data-toggle="modal" data-target="#modalNormas">
            Antes de enviar seu trabalho leia atentamente as regras de formatação.</a>


        <div class="form-group">
                <button type="submit" class="btn btn-success btn-block btn-lg">ATUALIZAR TRABALHO</button>
        </div>

    </form>



</div>


<div id="modalNormas" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Normas para a elaboração de trabalhos</h4>
            </div>
            <div class="modal-body">

                <p>Todos os trabalhos deverão apresentar o seguinte cabeçalho, que será preenchido nos campos apropriados:</p>
                <ul>
                    <li>Eixo temático</li>
                    <li>Modalidade do trabalho</li>
                    <li>Título e subtítulo, até 130 caracteres.</li>
                    <li>Nome(s) do autor(es), no máximo 3 autores, até 10 caracteres por autor.</li>
                    <li>Resumo, até 700 caracteres.</li>
                    <li>Palavras-chave, de 03 a 05 palavras, em letras minúsculas, separadas por ponto e vírgula.</li>
                </ul>
                <p><strong>RESUMO:</strong> Texto de síntese do trabalho, com até 700 caracteres. O texto será &ldquo;colado&rdquo; no campo apropriado do formulário online. O resumo não poderá conter tabelas, figuras, imagens, caracteres especiais, notas de rodapé, citações, referências ou os títulos das partes (introdução, objetivos etc). </p>
                <p><strong>PROPOSTA:</strong> Texto apresentando o objetivo do trabalho e o desenvolvimento da proposta do autor com bibliografia em Gestalt-terapia. As regras de citações, referências bibliográficas e quaisquer outras dúvidas deverão seguir as Normas da ABNT. O número de laudas irá variar conforme a modalidade escolhida (ver item 2) e incluirá as referências bibliográficas. Não serão aceitos gráficos, imagens e notas de rodapé. Consideraremos a lauda com 2.100 caracteres, com espaço. O texto deverá ser &ldquo;colado&rdquo; no formulário online, respeitando os campos referentes à proposta e à bibliografia<strong>. <span class="txt-laranja">O campo destinado à proposta não poderá conter o(s) nome do(s) autor(es)</span></strong>.</p>
                <p><strong>MINICURRÍCULOS:</strong> O autor e os coautores deverão enviar, através do formulário online apropriado, um texto com no máximo 500 caracteres contendo: nome, titulação, CRP ou CRM (se for o caso), instituição na qual trabalha, instituto ao qual está vinculado (se for o caso), indicação de onde fez a formação em Gestalt-terapia e o ano de conclusão, resumo de atividades desenvolvidas como gestalt-terapeuta e endereço para correspondência eletrônica (e-mail). Após a conclusão do processo de envio do trabalho, o autor receberá uma mensagem automática de confirmação.</p>


            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->