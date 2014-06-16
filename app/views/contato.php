<div id="page" class="outra-classe" <?php echo $this->pagina['adminbar'];?>>

    <h1 class="page-title">CONTATO</h1>

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





    <form action="<?php echo site_url('contato/post_contato'); ?>" class="form-horizontal" method="post" id="frm_contato">

        <div class="form-group">
            <label class="col-sm-2 control-label" for="field_nome">Nome</label>

            <div class="col-sm-8">
                <input type="text" id="field_nome" name="nome" class="form-control" required value="<?php echo
                set_value('nome')?>">
                <?php echo form_error('nome') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="field_email">E-mail</label>

            <div class="col-sm-8">
                <input type="email" id="field_email" name="email" class="form-control" required value="<?php echo
                set_value('email')?>">
                <?php echo form_error('email') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="field_tel">Telefone</label>

            <div class="col-sm-4">
                <input type="text" id="field_tel" name="tel" class="form-control" placeholder="(xx) xxxx-xxxx"
                       value="<?php echo set_value('tel') ?>" required pattern="\(\d{2,3}\) ?\d{4,5}-?\d{4}">
                <?php echo form_error('tel') ?>
            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-2 control-label" for="field_mensagem">Mensagem</label>

            <div class="col-sm-8">
                <textarea name="mensagem" id="field_mensagem" cols="30" rows="4" class="form-control"
                          require><?php echo
                    set_value('mensagem')?></textarea>
                <?php echo form_error('mensagem') ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-8 col-sm-push-2">
                <button type="submit" class="btn btn-success">ENVIAR MENSAGEM</button>
            </div>
        </div>

    </form>


</div>