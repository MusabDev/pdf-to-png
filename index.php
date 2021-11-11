<?php
include("vendor/autoload.php");

use \ConvertApi\ConvertApi;

ConvertApi::setApiSecret('ENTER_HERE_YOUR_SECRET_KEY');

$msg = "";
$contents = "";
$output = "";
if (isset($_POST["submit"])) {
  $filename = $_FILES["formFile"]["name"];
  $filetype = $_FILES["formFile"]["type"];
  $filetemp = $_FILES["formFile"]["tmp_name"];
  $dir = 'uploads/' . $filename;

  if ($filetype == "application/pdf") {
    move_uploaded_file($filetemp, $dir);
    $result = ConvertApi::convert(
      'png',
      [
        'File' => $dir,
      ],
      'pdf'
    );
    $contents = $result->getFile()->getContents();
    $output = "converted_files/" . rand() . ".png";
    $fopen = fopen($output, "w");
    fwrite($fopen, $contents);
    fclose($fopen);

    if ($result) {
      $msg = "<div class='alert alert-success'>File converted.</div>";
    } else {
      $msg = "<div class='alert alert-danger'>Something wrong.</div>";
    }
  } else {
    $msg = "<div class='alert alert-danger'>Invalid file format.</div>";
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <title>PDF to PNG - Pure Coding</title>
</head>

<body>
  <div class="container py-5">
    <div class="row">
      <div class="col-lg-5 mx-auto">
        <div class="card border p-4 rounded bg-white">
          <div class="card-body">
            <h3 class="card-title mb-3">PDF to PNG converter</h3>
            <?php echo $msg; ?>
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="formFile" class="form-label">Browse your file</label>
                <input class="form-control" type="file" id="formFile" name="formFile" required>
              </div>
              <button class="btn btn-primary" name="submit">Convert Now</button>
            </form>
            <img src="<?php echo $output; ?>" alt="" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>