<html>
<?php include(__DIR__.'/includes/head.php') ?>
    <body role="document">
        <div class="container theme-showcase" role="main">

            <div class="jumbotron">
                <h1>BOGOF SSL
                <img src="/bogof.jpg">
                </h1>
                <h2>Buy One, Get One Free!</h2>
                <p>
                Our certificates are 99.9%<super>&#42;</super> untrusted by all major (and minor) browsers.
                </p>

                <p>
                    We use the tried and trusted digest algorithms MD5 and MD4 only.
                </p>

                <p>
                    Feel free to put &#x1f512; this padloack all over your site it means very little.
                </p>

                <p>
                    You will need to create a Certificate Signing Request and for that will need openssl installed.

                </p>
            </div>
        <form method="POST" action="/csr_info">

        <label>Paste your Certificate Signing Request (.csr) file</label>

        <p>
        <textarea name="csr" rows="30" cols="100"><?php if(isset($_POST['csr'])) echo $_POST['csr']; ?></textarea>
        </p>

        <input type="submit" class="btn btn-lg btn-primary">
        </form>

        <super>&#42;</super>&#177;0.1%
    </div>
    </body>
</html>