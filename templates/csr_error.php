<html>
<?php include(__DIR__.'/includes/head.php') ?>
    <body role="document">
        <div class="container theme-showcase" role="main">

            <div class="jumbotron">
                <p>
                    This does not look like a Certificate Signing Request to me
                </p>

                <p>
                    <?= htmlentities($csr) ?>
                </p>
        </div>
    </body>
</html>