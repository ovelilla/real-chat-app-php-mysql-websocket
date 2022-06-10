<?php include __DIR__ . '/header.php' ?>

<span class="preheader">Mensaje de contacto desde la web</span>

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
                                        <h1>Mensaje</h1>
                                        <p>Nombre: <?php echo $this->data->getName()  ?></p>
                                        <p>Apellidos: <?php echo $this->data->getSurname()  ?></p>
                                        <p>Email: <?php echo $this->data->getEmail()  ?></p>
                                        <p>Teléfono: <?php echo $this->data->getPhone()  ?></p>
                                        <p>Mensaje: <?php echo $this->data->getMessage()  ?></p>
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