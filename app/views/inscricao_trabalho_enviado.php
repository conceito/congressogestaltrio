<div id="page" class="outra-classe">

    <h1 class="page-title">INSCRIÇÃO de trabalho</h1>


    <?php if ($this->msg): ?>
        <div class="alert alert-<?php echo ($this->msg_type == 'error') ? 'danger' : $this->msg_type ?>">
            <?php echo $this->msg ?>
        </div>
    <?php endif; ?>

    <p>Prezado(a) <?php echo $user['nome'] ?>,
        <br/>
        <br/>
        Seu trabalho foi enviado com sucesso. <br/>
        Por favor, aguarde o retorno da Comissão Científica.

    </p>

    <p>Atenciosamente,
        <br/>
        Comissão Organizadora</p>


</div>