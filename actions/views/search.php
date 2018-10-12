<div style="text-align: center; margin: 0px">
    <h3>Search</h3>
</div>

<style type="text/css">
.form-search {
  width: 100%;
  max-width: 500px;
  padding: 15px;
  margin: 0 auto;
}

.form-search .form-control {
  box-sizing: border-box;
  height: auto;
  padding: 5px;
  font-size: 16px;
  width: 10em;
}

.form-search .btn-primary {
  padding: 10px;
  width: 9em;
}
</style>

<script>
    $(function() {
        $("#date_from, #date_to").datepicker({
            dateFormat: "yy-mm-dd"
        });

        $('#search-form').submit(function(){
            if ('' == $('#number').val()) {
                alert('Please enter a number!');
                return false;
            }

            if ('' == $('#duration').val()) {
                alert('Please enter the duration!');
                return false;
            }

            $('#submit').attr('disabled', 'diabled');
            $('#loader').show();
        });
    });
</script>

<form class="form form-search" method="post" id="search-form" onsubmit="">
    <div class="form-group row">
        <label for="number" class="col-sm-4 col-form-label">Phone number:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="number" id="number" placeholder="(999) 123-1234" value="<?php echo !empty($_REQUEST['number']) ? $_REQUEST['number'] : ''?>"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="date_from" class="col-sm-4 col-form-label">Date from:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="date_from" id="date_from" autocomplete="off" value="<?php echo !empty($_REQUEST['date_from']) ? $_REQUEST['date_from'] : ''?>"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="date_to" class="col-sm-4 col-form-label">Date to:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control datepicker" name="date_to" id="date_to" autocomplete="off" value="<?php echo !empty($_REQUEST['date_to']) ? $_REQUEST['date_to'] : ''?>"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="duration" class="col-sm-4 col-form-label">Duration:</label>
        <div class="col-sm-8">
            <input type="number" class="form-control datepicker" name="duration" id="duration" style="width: 6em;" placeholder="seconds" value="<?php echo !empty($_REQUEST['duration']) ? $_REQUEST['duration'] : ''?>"/>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="submit" id="submit" value="Search"/>
    </div>
</form>

<?php if (empty($_POST)) {
  return;
} ?>

<?php if (empty($searchResultsCount)) {
  echo '<p class="text-muted">Nothing found.</small></p>';
  return;
} ?>


<h5>Results count: <?php echo $searchResultsCount?></h5>
<a target="_blank" href="index.php?page=generate-xls&<?php echo http_build_query($_POST);?>">Download Excel file</a>