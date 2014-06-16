<div id="page" class="outra-classe" <?php echo $this->pagina['adminbar'];?>>
	
    <h1 class="page-title"><?php echo $this->pagina['titulo'];?></h1>
	
    <?php 
	if(isset($this->pagina['children'])):		
		echo $this->pagina['children'];		
	endif;
	?>

    
	<?php // Conteúdo parseado
	echo $this->pagina['txt'];?>
    
    
            
            
</div>