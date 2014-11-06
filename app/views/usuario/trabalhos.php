<?php
/**
 * list of jobs submited
 */
?>
<div id="page" class="outra-classe">

	<h1 class="page-title">Meus trabalhos</h1>

	<?php if ($this->msg): ?>
		<div class="alert alert-<?php echo ($this->msg_type == 'error') ? 'danger' : $this->msg_type ?>">
			<?php echo $this->msg ?>
		</div>
	<?php endif; ?>

	<table class="table table-striped table-avaliacao">
		<thead>
		<tr>
			<th></th>
			<th class="">Título</th>
			<th>Status</th>
			<th>Data</th>
			<th>Ações</th>
		</tr>
		</thead>
		<tbody>


		<?php
		if (isset($jobs) && $jobs):

		foreach ($jobs as $job):
			?>
			<tr class="avaliacao-item">
				<td>
					<?php if ($job['grupo_nick'] == 'aprovado'){ ?>
						<i class="glyphicon glyphicon-ok"></i>
					<?php } ?>

						<a href="#" data-toggle="modal" data-target="#comments-<?php echo $job['id']?>" title="Ler comentários"><i class="glyphicon glyphicon-file"></i></a>

				</td>
				<td class="title"><?php echo $job['titulo_txt'] ?></td>
				<td class="status "><?php echo $job['grupo_titulo']?></td>
				<td class="date"><?php echo $job['dt_ini'] ?></td>
				<td class="actions">
					<?php if ($job['grupo_nick'] == 'aprovado-com-solicitacao-de-correcoes')
					{ ?>
						<a href="<?php echo site_url('usuario/trabalho/' . $job['id']) ?>" class="btn btn-primary">
							Editar </a>
					<?php } else { ?>
						-
					<?php } ?>

				</td>
			</tr>




		<?php
		endforeach;
		?>
		</tbody>
	</table>

<?php
else:
	?>
	<p>Não existem trabalhos.</p>
<?php
endif;
?>


</div>


<?php
if (isset($jobs) && $jobs):

foreach ($jobs as $job):
?>
	<div id="comments-<?php echo $job['id']?>" class="modal fade">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Comentários</h4>
				</div>
				<div class="modal-body">
					<p><?php echo $job['evaluation']['comments']?></p>
				</div>

			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php

endforeach;

endif;
?>
