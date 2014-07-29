<?php
/**
 * Página do usuário
 */
?>

<div id="page" class="outra-classe">

	<h1 class="page-title">Olá <?php echo $user['nome']?></h1>

	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading"><strong>O que deseja fazer</strong></div>
		<!--	<div class="panel-body">-->
		<!--		<p></p>-->
		<!--	</div>-->

		<!-- List group -->
		<ul class="list-group">
			<li class="list-group-item"><a href="<?php echo site_url('usuario/trabalhos') ?>">Ver meus trabalhos
					submetidos</a></li>
			<li class="list-group-item"><a href="<?php echo site_url('usuario/logout') ?>">Sair</a></li>
		</ul>
	</div>

</div>