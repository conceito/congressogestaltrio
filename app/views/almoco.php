<div id="page" class="outra-classe">

	<h1 class="page-title">Almoço</h1>


	<p>Prezados colegas,</p>

	<p>A organização do V Congresso de Gestalt-terapia do Estado do Rio de Janeiro tem o prazer de informar que contaremos com a opção de almoço no local do congresso.</p>


	<p>O almoço será sob a forma de buffet self-service com direito a 1 sobremesa e 1 refresco por refeição.</p>

	<p>O custo total do almoço para os 3 dias do evento será de R$75,00.</p>


	<p>Este valor poderá ser parcelado em 2 x R$ 37,50, com vencimentos em 15/10 e 10/11.</p>


	<p>Após o pagamento envie o comprovante para o e-mail
		<a href="mailto:almoco@congressogestaltrio.com.br">almoco@congressogestaltrio.com.br</a>
	</p>


	<p>
		<strong>Importante:</strong> Quem desejar almoçar 1 ou 2 dias, deverá comprar o seu ticket na secretaria do congresso no dia do evento, mas comunicamos que o preço será de R$30,00 (cada dia) e, devido ao limite de espaço, só serão disponibilizados tickets avulsos por dia se sobrarem da venda antecipada.
	</p>
	<p><strong>Após clicar em "Enviar inscrição"  aguarde o e-mail com dados bancários para realizar o depósito.</strong></p>




	<?php if ($this->msg): ?>
		<div class="alert alert-<?php echo ($this->msg_type == 'error') ? 'danger' : $this->msg_type ?>">
			<?php echo $this->msg ?>
		</div>
	<?php endif; ?>

	<script type="text/javascript">
		$(document).ready(function () {

			$('form#almoco').validate();
			$('#field_telefone').mask("(99) 99999999?9");

			if (CMS.form.returnType == 'success') {
				$('.well-form').hide();
				$('.open-form').on('click', function () {
					$('.well-form').slideDown();
					$(this).hide();
				});
			}
			else {
				$('.open-form').hide();
			}


		});
	</script>

	<a href="#" class="open-form btn">Fazer outra inscrição</a>

	<div class="well well-form">

		<form action="<?php echo site_url('inicio/post_almoco') ?>" method="post" id="almoco" class="form-horizontal">


			<fieldset>
				<legend>Efetue sua compra do almoço pelo formulário abaixo:</legend>


				<div class="form-group <?php err('nome') ?>">
					<label class="col-sm-3 control-label" for="field_nome">Nome</label>

					<div class="col-sm-8">
						<input type="text" id="field_nome" name="nome" class="form-control" required value="<?php
						echo
						set_value('nome')?>">
						<?php echo form_error('nome') ?>
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

				<div class="form-group <?php err('telefone') ?>">
					<label class="col-sm-3 control-label" for="field_telefone">Telefone</label>

					<div class="col-sm-4">
						<input type="tel" id="field_telefone" name="telefone" class="form-control" required value="<?php echo
						set_value('telefone')?>">
						<?php echo form_error('telefone') ?>
					</div>
				</div>


				<div class="form-group <?php err('pagamento') ?>">
					<!--				<label class="col-sm-3 control-label" for="field_pagamento">Telefone</label>-->

					<div class="col-sm-8 col-sm-offset-3">

						<p>Eu desejo comprar o ticket de almoço para três dias de congresso e minha opção de pagamento é:</p>

						<div class="radio">
							<label>
								<input type="radio" name="pagamento" class="-form-control" required value="à vista R$ 75,00" <?php echo set_radio('pagamento', 'à vista R$ 75,00') ?>>
								<strong>à vista R$ 75,00</strong> </label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="pagamento" class="-form-control" required value="parcelado  2x R$37,50" <?php echo set_radio('pagamento', 'parcelado  2x R$37,50') ?>>
								<strong>parcelado 2x R$37,50</strong> </label>
						</div>
						<?php echo form_error('pagamento') ?>
					</div>
				</div>

			</fieldset>


			<div class="form-group">
				<div class="col-sm-8 col-sm-push-3">
					<button type="submit" class="btn btn-success">ENVIAR INSCRIÇÃO</button>
				</div>
			</div>


		</form>

	</div>


</div>