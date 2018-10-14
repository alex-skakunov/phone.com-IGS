<div style="text-align: center; margin-bottom: 50px;">
  <h1>
    Settings
  </h1>
</div>

<? if (!empty($errorMessage)): ?>
  <div class="alert alert-danger" role="alert">
    <?=$errorMessage?>
  </div>
  <br/>
<? endif; ?>

<? if (!empty($message)): ?>
  <div class="alert alert-info" role="alert">
    <?=$message?>
  </div>
  <br/>
<? endif; ?>

<form method="post" enctype="multipart/form-data" onsubmit="$('#loader').show();">
   <table border="0" align="center">
    <tr>
      <td><label for="user_password">Change the password:</label></td>
      <td width="10px">&nbsp;</td>
      <td><input type="password" name="user_password" id="user_password" class="edt" /></td>
      <td>&nbsp;</td>
      <td><input id="user_submit" type="Submit" name="user_submit" value="Save" class="btn btn-primary" style="padding: 3px 15px" /></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
</form><hr/>

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
