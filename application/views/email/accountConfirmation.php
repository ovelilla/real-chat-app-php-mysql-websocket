<?php include __DIR__ . '/header.php' ?>

<span class="preheader">Email para restablecer tu contraseña en lacopiadera.com</span>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td class="container">
            <div class="content">
                <table role="presentation" class="main">
                    <tr>
                        <td class="wrapper">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <h1>¡Bienvenido!</h1>
                                        <p><strong>Hola <?php echo $this->data->getName()  ?></strong></p>
                                        <p>Acabas de crear una nueva cuenta en lacopiadera.com. Para completar el registro, por favor confirma tu cuenta haciendo clic en el siguiente enlace.</p>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                            <tbody>
                                                <tr>
                                                    <td align="center">
                                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <a href="<?php echo $this->domain ?>/confirmar/<?php echo $this->data->getToken() ?>" target="_blank">Confirmar cuenta</a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p>¿No funciona el enlace? Copia la siguiente URL en tu navegador.</p>
                                        <p><a href="<?php echo $this->domain ?>/confirmar/<?php echo $this->data->getToken() ?>" target="_blank" class="dont-break-out"><?php echo $this->domain ?>/confirmar/<?php echo $this->data->getToken() ?></a></p>
                                        <p>Si no has creado esta cuenta, puedes ignorar este mensaje.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <div class="footer">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-block">
                                <span class="apple-link">Lacopiadera.com</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>

</body>

</html>