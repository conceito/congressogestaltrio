<div id="page" class="outra-classe">

<h1 class="page-title">INSCRIÇÃO no congresso</h1>

<div class="form-horizontal">

	<div class="form-group">
		<div class="col-sm-8 col-sm-push-2">
			<p>Todos os campos são obrigatórios.</p>
		</div>
	</div>
</div>



<?php if ($this->msg): ?>
	<div class="alert alert-<?php echo ($this->msg_type == 'error') ? 'danger' : $this->msg_type ?>">
		<?php echo $this->msg ?>
	</div>
<?php endif; ?>





<form action="<?php echo site_url('inscricao/post_inscricao'); ?>" class="form-horizontal -form-validate" method="post"
      id="frm_inscricao">


<?php
/**
 * checkbox para debugar submissão
 */
if ($this->phpsess->get('logado', 'cms')):?>
	<input type="checkbox" name="debug_mode" value="1"> debug
<?php endif; ?>

<fieldset>
	<legend>Dados pessoais</legend>

	<div class="form-group <?php err('nome') ?>">
		<label class="col-sm-3 control-label" for="field_nome">Nome</label>

		<div class="col-sm-8">
			<input type="text" id="field_nome" name="nome" class="form-control" required value="<?php echo
			set_value('nome')?>">
			<?php echo form_error('nome') ?>
		</div>
	</div>

	<div class="form-group <?php err('sobrenome') ?>">
		<label class="col-sm-3 control-label" for="field_sobrenome">Último nome</label>

		<div class="col-sm-8">
			<input type="text" id="field_sobrenome" name="sobrenome" class="form-control" required value="<?php echo
			set_value('sobrenome')?>">
			<?php echo form_error('sobrenome') ?>
		</div>
	</div>


	<div class="form-group <?php err('cracha') ?>">
		<label class="col-sm-3 control-label" for="field_cracha">Nome no crachá</label>

		<div class="col-sm-8">
			<input type="text" id="field_cracha" name="cracha" class="form-control" required value="<?php echo
			set_value('cracha')?>">
			<?php echo form_error('cracha') ?>
		</div>
	</div>

	<div class="form-group <?php err('email') ?>">
		<label class="col-sm-3 control-label" for="field_email">E-mail</label>

		<div class="col-sm-8">
			<input type="email" id="field_email" name="email" class="form-control" required value="<?php echo
			set_value('email')?>">
			<?php echo form_error('email') ?>
		</div>
	</div>

	<div class="form-group <?php err('cpf') ?>">
		<label class="col-sm-3 control-label" for="field_cpf">CPF</label>

		<div class="col-sm-4">
			<input type="text" id="field_cpf" name="cpf" class="form-control" placeholder="xxx.xxx.xxx-xx"
			       value="<?php echo set_value('cpf') ?>" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}">
			<?php echo form_error('cpf') ?>
		</div>
		<span class="help-block">xxx.xxx.xxx-xx</span>
	</div>

	<div class="form-group <?php err('tel1') ?>">
		<label class="col-sm-3 control-label" for="field_tel">Telefone</label>

		<div class="col-sm-4">
			<input type="text" id="field_tel" name="tel1" class="form-control" placeholder="(xx) xxxx-xxxx"
			       value="<?php echo set_value('tel1') ?>" required pattern="\(\d{2,3}\) ?\d{4,5}-?\d{4}">
			<?php echo form_error('tel1') ?>
		</div>
		<span class="help-block">(xx) xxxx-xxxx</span>
	</div>

	<div class="form-group <?php err('sexo') ?>">
		<label class="col-sm-3 control-label" for="field_sexo">Sexo</label>

		<div class="col-sm-4">
			<select name="sexo" id="field_sexo" class="form-control">
				<option value="0" <?php echo set_select('sexo', '0', TRUE); ?>>Masculino</option>
				<option value="1" <?php echo set_select('sexo', '1'); ?>>Feminino</option>
			</select>
		</div>
	</div>


	<div class="form-group <?php err('fantasia') ?>">
		<label class="col-sm-3 control-label" for="field_fantasia">Instituição a qual pertence</label>

		<div class="col-sm-8">
			<input type="text" id="field_fantasia" name="fantasia" class="form-control" value="<?php echo
			set_value('fantasia')?>">
			<?php echo form_error('fantasia') ?>
		</div>
	</div>

</fieldset>


<fieldset>
	<legend>Endereço</legend>

	<div class="form-group <?php err('pais') ?>">
		<label class="col-sm-3 control-label" for="field_pais">País</label>

		<div class="col-sm-4">
			<?php echo $combo_paises ?>
			<?php echo form_error('pais') ?>
		</div>

	</div>

	<div class="form-group <?php err('cep') ?>">
		<label class="col-sm-3 control-label" for="field_cep">CEP</label>

		<div class="col-sm-4">
			<input type="text" id="field_cep" name="cep" class="form-control" placeholder="xx.xxx-xxx"
			       value="<?php echo set_value('cep') ?>" required>
			<?php echo form_error('cep') ?>

		</div>
		<span class="help-block">Apenas números</span>
	</div>

	<div class="form-group">

		<div class="loading-uf ">Carregando</div>
		<label class="col-sm-3 control-label" for="field_uf">UF / Cidade</label>

		<div class="col-sm-2 <?php err('uf') ?>">
			<?php echo $combo_uf ?>
			<?php echo form_error('uf') ?>
		</div>
		<div class="col-sm-6 <?php err('cidade') ?>">
			<div id="cidade-ajax">
				<?php echo $combo_cidades ?>
			</div>

			<?php echo form_error('cidade') ?>
		</div>

	</div>

	<div class="form-group <?php err('bairro') ?>">
		<label class="col-sm-3 control-label" for="field_bairro">Bairro</label>

		<div class="col-sm-8">
			<input type="text" id="field_bairro" name="bairro" class="form-control" value="<?php echo
			set_value('bairro')?>">
			<?php echo form_error('bairro') ?>
		</div>
	</div>

	<div class="form-group <?php err('logradouro') ?>">
		<label class="col-sm-3 control-label" for="field_logra">Logradouro</label>

		<div class="col-sm-8">
			<input type="text" id="field_logra" name="logradouro" class="form-control" value="<?php echo
			set_value('logradouro')?>" placeholder="Rua, Av., Estrada...">
			<?php echo form_error('logradouro') ?>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label" for="field_num">Número / Complemento</label>

		<div class="col-sm-4 <?php err('num') ?>">
			<input type="text" id="field_num" name="num" class="form-control" value="<?php echo
			set_value('num')?>" required placeholder="Número">
			<?php echo form_error('num') ?>
		</div>
		<div class="col-sm-4 <?php err('compl') ?>">
			<input type="text" id="field_compl" name="compl" class="form-control" value="<?php echo
			set_value('compl')?>" placeholder="Complemento">
			<?php echo form_error('compl') ?>
		</div>
	</div>

</fieldset>

<fieldset>
	<legend>Dados do pagamento</legend>

	<div class="form-group <?php err('pagamento') ?>">
		<label class="col-sm-3 control-label" for="field_pagamento">Instituição</label>

		<div class="col-sm-8">

			<select name="pagamento" id="field_pagamento" class="form-control">
				<option value="">Escolha...</option>
				<option value="contato" <?php echo set_select('pagamento', 'contato'); ?>>Contato | Núcleo de Estudos e
					Aplicação da Gestalt-Terapia (Banco Santander)
				</option>
				<option value="dialogo" <?php echo set_select('pagamento', 'dialogo'); ?>>Dialógico | Núcleo de
					Gestalt-terapia (Banco Itaú)
				</option>
				<option value="gestalt" <?php echo set_select('pagamento', 'gestalt'); ?>>Instituto de Psicologia
					Gestalt em Figura (Banco Itaú)
				</option>
				<option value="igt" <?php echo set_select('pagamento', 'igt'); ?>>IGT | Instituto de Gestalt-Terapia e
					Atendimento Familiar (Banco do Brasil ou Banco Itaú)
				</option>
			</select>
			<?php echo form_error('pagamento') ?>
		</div>
	</div>

	<div class="form-group <?php err('tipo_usuario') ?>">
		<label class="col-sm-3 control-label" for="field_tipo_usuario">Você é</label>

		<div class="col-sm-8">

			<select name="tipo_usuario" id="field_tipo_usuario" class="form-control">
				<option value="">Escolha...</option>

				<option value="profissional" <?php echo set_select('tipo_usuario', 'profissional'); ?>>
					Profissional
				</option>
				<!--
				<option value="profissional_grupo" <?php echo set_select('tipo_usuario',
					'profissional_grupo'); ?>>Profissional grupo
				</option> -->
				<option value="especializacao" <?php echo set_select('tipo_usuario', 'especializacao'); ?>>Estudante de
					especialização
				</option>
				<!--
				<option value="especializacao_grupo" <?php echo set_select('tipo_usuario',
					'especializacao_grupo'); ?>>Estudante de especialização em grupo
				</option> -->
				<option value="graduacao" <?php echo set_select('tipo_usuario', 'graduacao'); ?>>Estudante de
					graduação
				</option>

			</select>
			<?php echo form_error('tipo_usuario') ?>
		</div>
	</div>

	<div class="form-coord">

		<div class="form-group <?php err('nome_coord') ?> <?php err('email_coord') ?>">
			<label class="col-sm-3 control-label" for="field_nome_coord">Nome do coordenador do grupo</label>

			<div class="col-sm-8">
				<input type="text" id="field_nome_coord" name="nome_coord" class="form-control" value="<?php echo
				set_value('nome_coord')?>">
				<?php echo form_error('nome_coord') ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="field_email_coord">E-mail do coordenador do grupo</label>

			<div class="col-sm-8">
				<input type="email" id="field_email_coord" name="email_coord" class="form-control" value="<?php echo
				set_value('email_coord')?>">
				<?php echo form_error('email_coord') ?>
			</div>
		</div>

	</div>
	<!-- form-coord -->

	<div class="form-group <?php err('forma_pagamento') ?>">
		<label class="col-sm-3 control-label" for="field_forma_pagamento">Forma de pagamento</label>

		<div class="col-sm-8">

			<select name="forma_pagamento" id="field_forma_pagamento" class="form-control">
				<option value="a-vista" <?php echo set_select('forma_pagamento', 'a-vista', TRUE); ?>>à vista</option>
				<?php if ($parcelas): ?>
					<option value="parcelado" <?php echo set_select('forma_pagamento', 'parcelado'); ?>>parcelado
						(<?php echo $parcelas ?>x)
					</option>
				<?php endif; ?>
			</select>
			<?php echo form_error('forma_pagamento') ?>
		</div>
	</div>

</fieldset>

<fieldset>
	<legend>Senha</legend>

	<p>Sua senha deve ter pelo menos 6 caracteres.</p>

	<div class="form-group <?php err('senha') ?>">
		<label class="col-sm-3 control-label" for="field_senha">Senha</label>

		<div class="col-sm-4">
			<input type="password" id="field_senha" name="senha" class="form-control" value="<?php echo
			set_value('senha')?>" required pattern="\S{6,}">
			<?php echo form_error('senha') ?>
		</div>
	</div>

	<div class="form-group <?php err('confirmacao') ?>">
		<label class="col-sm-3 control-label" for="field_confirmacao">Confirmar senha</label>

		<div class="col-sm-4">
			<input type="password" id="field_confirmacao" name="confirmacao" class="form-control" value="<?php echo
			set_value('confirmacao')?>" required pattern="\S{6,}">
			<?php echo form_error('confirmacao') ?>
		</div>
	</div>

</fieldset>


<div class="form-group">
	<div class="col-sm-8 col-sm-push-3">
		<button type="submit" class="btn btn-success">ENVIAR MENSAGEM</button>
	</div>
</div>

</form>


</div>