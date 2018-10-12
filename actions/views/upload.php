<div style="text-align: center; margin-bottom: 50px;">
  <h1>
    Upload a new file
  </h1>
</div>

<form method="post" enctype="multipart/form-data" onsubmit="$('#submit').attr('disabled', 'diabled'); $('#loader').show();">
   <input type="hidden" name="version" value="1.0" />
   <table border="0" align="center">
    <tr>
      <td>Excel file to upload:</td>
      <td rowspan="30" width="10px">&nbsp;</td>
      <td><input type="file" name="file_source[]" id="file_source" class="edt" value="<?php echo $file_source?>" accept=".csv, .zip" multiple="multiple" /></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <input id="submit" type="Submit" name="Go" value="Upload" class="btn btn-primary" style="padding: 10px 15px" onclick="var s = document.getElementById('file_source'); if(null != s && '' == s.value) {alert('Please pick a file'); s.focus(); return false;}" />
      </td>
    </tr>
  </table>
</form>

<?php if(!empty($uploadResultsList)): 
  $i = 1;
?>
<br/>
<table class="table" style="width: 500px" align="center">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Filename</th>
      <th scope="col">CSV records</th>
      <th scope="col">Imported records</th>
      <th scope="col">Duplicates removed</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($uploadResultsList as $filename => $result): ?>
    <tr>
      <th scope="row"><?php echo $i++?></th>
      <th scope="row"><?php echo $filename?></th>
      <?php if ('error' == $result['status']) : ?>
        <td class="text-left" colspan="3">
          <i class="fa fa-exclamation-triangle"></i>
          <i><?php echo $result['error_message']; ?></i>
        </td>
      <?php else : ?>
        <td><?php echo $result['csv_rows_count']; ?></td>
        <td><?php echo $result['unique_records']; ?></td>
        <td><?php echo $result['duplicate_records']; ?></td>
      <?php endif; ?>

    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
