<style type="text/css">
	.job-label {
		font-weight: 700;
		font-size: 17px;
		color: #000;
		text-transform: uppercase;
		margin: 0 0 10px;
	}

	.job-content {
		margin: 5px 0 3em;
		line-height: 20px;
	}

	.titulo {
		font-weight: 700;
		font-size: 18px;
	}

	.main p {
		/*text-indent: 30px;*/
		margin: 0 0 10px;
	}

</style>

<?php if (isset($eixo_tematico)): ?>
	<div class="job-label">Eixo temático</div>
	<div class="job-content">
		<?php echo $eixo_tematico ?>
	</div>
<?php endif; ?>

<?php if (isset($modalidade)): ?>
	<div class="job-label">Modalidade</div>
	<div class="job-content">
		<?php echo $modalidade ?>
	</div>
<?php endif; ?>

<?php if (isset($titulo)): ?>

	<div class="job-content titulo">
		<br/>
		<?php echo $titulo ?>
	</div>
<?php endif; ?>


<?php if (isset($authors)): ?>

	<span class="job-content">
		<p>
			<?php foreach ($authors as $a): ?>

				<?php echo $a['nome'] ?>

				<br/>

			<?php endforeach; ?>
		</p>
	</span>
<?php endif; ?>

<?php if (isset($subtitulo)): ?>
	<div class="job-label">Subtítulo</div>
	<div class="job-content">
		<?php echo $subtitulo ?>
	</div>
<?php endif; ?>

<?php if (isset($resumo)): ?>
	<br/>
	<br/>
	<div class="job-label">Resumo</div>
	<div class="job-content">
		<?php echo $resumo ?>
	</div>
<?php endif; ?>

<?php if (isset($tags)): ?>
	<div class="job-content">
		<strong>Palavras-chave:</strong> <?php echo $tags ?>
	</div>
<?php endif; ?>

<?php if (isset($txt)): ?>
	<br/>
	<br/>
	<div class="job-label">Proposta</div>

	<span class="job-content main">
			<?php echo $txt ?>
	</span>
<?php endif; ?>


<?php if (isset($authors)): ?>

	<br/>
	<br/>
	<span class="job-content">
		<?php foreach ($authors as $a): ?>

			<p>
				<strong><?php echo $a['nome'] ?></strong>
				<?php echo str_replace(array('<p>', '</p>'), '', clean_html_to_db($a['curriculo'])) ?>
				<?php //echo str_replace(array('<p>','</p>'), '', $a['curriculo'])?>
			</p>

		<?php endforeach; ?>
	</span>
<?php endif; ?>