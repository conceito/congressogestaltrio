<div id="page" class="outra-classe">

    <h1 class="page-title">INSCRIÇÃO NO CONGRESSO</h1>


    <?php if ($this->msg): ?>
        <div class="alert alert-<?php echo ($this->msg_type == 'error') ? 'danger' : $this->msg_type ?>">
            <?php echo $this->msg ?>
        </div>
    <?php endif; ?>

    <p>Prezado(a) <?php echo $user['nome'] ?>,
        <br/>
        Agora, para dar prosseguimento ao processo de inscrição, siga os passos abaixo:
    </p>


    <ol>
        <li>Efetue o depósito para a instituição escolhida;</li>
        <li>Envie imediatamente o comprovante de depósito para o e-mail: <a
                href="mailto:contato@congressogestaltrio.com.br">contato@congressogestaltrio.com.br</a>;
        </li>
        <li>Guarde o e-mail que enviaremos confirmando a identificação do seu depósito na conta corrente da instituição
            escolhida. Este e-mail será a sua garantia de que a sua inscrição foi concluída.
        </li>
    </ol>

    <div class="well">
        <p>
            <strong>Dados para depósito:</strong>
        </p>

        <p>
            Você escolheu o <strong><?php echo $institution->getName() ?></strong> <br/><?php echo $institution->getPhone()?>
        </p>
        <table class="table table-bordered">

            <?php
            $bank = $institution->getBank();
            if (!isset($bank[0]) || !is_array($bank[0])) {
                $bank = array($bank);
            }

            foreach ($bank as $b):
                ?>
                <tr>
                    <td valign="top"><strong><?php echo $b['name']?></strong></td>
                    <td valign="top">
                        Agência: <?php echo $b['agency']?>
                        <br/>
                        Conta corrente: <?php echo $b['account']?>
                        <br/>
                        Favorecido: <?php echo $b['owner']?>
                        <br/>
                        <?php echo $b['doc']?>
                    </td>
                </tr>

            <?php
            endforeach;
            ?>
            <tr>
                <td valign="top">Data de vencimento:</td>
                <td valign="top">
                    <?php //echo formaPadrao(next_valid_pay($table['to'])) ?>
                    <?php echo formaPadrao($table['to']) ?>

                    <?php if($forma_pagamento == 'parcelado'):?>
                        <br/>As demais parcelas devem ser feitas no dia 10 dos meses seguintes.
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <td valign="top">Valor:</td>
                <td valign="top">R$ <?php echo formaBR($table['value']) ?></td>
            </tr>

            <?php if($forma_pagamento == 'parcelado'):?>
            <tr>
                <td valign="top">N° de parcelas:</td>
                <td valign="top"><?php echo $table['portion']['times'] ?>x de
                    R$ <?php echo formaBR($table['portion']['value']) ?></td>
            </tr>
            <?php endif;?>
	        <tr>
		        <td valign="top">Tipo:</td>
		        <td valign="top"><?php echo ($grupo) ? 'Em grupo' : 'Individual'?></td>
	        </tr>
        </table>

    </div>


    <p>Um abraço,
        <br/>
        Comissão Organizadora</p>


</div>