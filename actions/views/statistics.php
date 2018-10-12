<div style="text-align: center; margin-bottom: 50px;">
  <h1>
     Statistics
  </h1>
</div>

<?php if (!empty($message)): ?>
  <div class="alert alert-info" role="alert">
    <?php echo $message?>
  </div>
  <br/>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" onsubmit="$('#loader').show();">
   <table border="0" align="center">
    <tr>
      <td>
        <h4>Logs records available: <?php echo number_format($totalCount)?></h4>
      </td>
    </tr>
    <tr>
      <td>
        <br/>
        <input type="submit" name="erase_database" id="erase_database" class="btn btn-primary" value="Erase all records" /></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
</form>
