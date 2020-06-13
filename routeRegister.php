<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="CSS/freestyle.css">
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/fontawesome.min.css">
    <link rel="stylesheet" href="CSS/all.css">
    <title><?php $apath=$_SERVER['PHP_SELF']; $bits=explode("/",$apath); echo $bits[2]; ?></title>
</head>
<body>
    

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="container m-1 p-1">
                <?php @include 'register.php'; ?>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
    <!-- <script>
        alert('ok');
        const activateUploadBtn = (event) => {
            event.preventDefault();
            document.querySelector("input[type='file']").click();
        }

    </script> -->

</body>
</html>