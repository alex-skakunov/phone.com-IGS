<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title><?php echo ucfirst(CURRENT_ACTION)?> — Invoice Generation System</title>
    <!-- Bootstrap core CSS -->

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Custom styles for this template -->
    <style type="text/css">
    .starter-template {
      text-align: center;
    }

    label.disabled {
      color: lightgray;
    }
    
    body
    {
      background-color: #f5f5f5;
      padding-top: 5rem;
    }
    
    .edt
    {
      background:#ffffff; 
      border:3px double #aaaaaa; 
      -moz-border-left-colors:  #aaaaaa #ffffff #aaaaaa; 
      -moz-border-right-colors: #aaaaaa #ffffff #aaaaaa; 
      -moz-border-top-colors:   #aaaaaa #ffffff #aaaaaa; 
      -moz-border-bottom-colors:#aaaaaa #ffffff #aaaaaa; 
      width: 350px;
    }
    .edt_30
    {
      background:#ffffff; 
      border:3px double #aaaaaa; 
      font-family: Courier;
      -moz-border-left-colors:  #aaaaaa #ffffff #aaaaaa; 
      -moz-border-right-colors: #aaaaaa #ffffff #aaaaaa; 
      -moz-border-top-colors:   #aaaaaa #ffffff #aaaaaa; 
      -moz-border-bottom-colors:#aaaaaa #ffffff #aaaaaa; 
      width: 30px;
    }
    
    input {
      font-size: 16px
    }
    input.btn
    {
      font-weight: bold;
      padding: 5px;
    }
    
    input.auto-map
    {
      font-weight: normal;
      font-size: 70%;
    }

    td {
      text-align: center;
    }

    table#search-form {
      margin-bottom: 100px;
      font-size: 90%;
    }

    table#search-form td {
      text-align: left;
    }

    table#search-form th {
      text-align: right;
    }

    table#search-form input[type="text"],
    table#search-form textarea {
      width: 30em;
    }

    table#search-form input[type="number"] {
      margin: 0 20px;
    }

    table#search-form input.small-field {
      width: 6em;
    }

    table#search-form input#firstname,
    table#search-form input#lastname {
      width: 15em;
    }

    table#search-form input#submit {
      margin-top: 5px;
      padding: 10px 35px;
    }
  </style>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">

          <?
            $itemsList = array('upload', 'search');
            foreach ($itemsList as $item) {
              echo '<li class="nav-item ', (CURRENT_ACTION == $item ? 'active' : ''), '">';
              echo '<a class="nav-link" href="index.php?page=' . $item . '">' . ucfirst($item) . '</a>';
              echo '</li>'; 
            }
          ?>
        </ul>
      </div>

      <div style="float: right;" class="nav-item">
        <a  style="text-decoration: none; color: gray;" class="nav-link" href="index.php?page=statistics"><small>Statistics</small></a>
      </div>
    </nav>

    <main role="main">

      <?php if (!empty($error)) : ?>
        <div class="alert alert-danger text-center" role="alert">
          <?php echo $error;?>
        </div>
      <?php endif; ?>

      <div class="starter-template">
        [template]
      </div>

      <div style="display: none; text-align: center; margin-top: 20px" id="loader">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/de/Ajax-loader.gif" width="32" height="32" alt="loader" />
      </div>
    </main><!-- /.container -->

</body>
</html>