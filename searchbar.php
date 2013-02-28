<form name='search' action=index.php method='GET'>
  <table>
		<tr>
			<td><select name='type'>
					<option value='all' <?php if($_GET["type"]=="all") echo "selected";?>>All</option>
					<option value='song' <?php if($_GET["type"]=="song") echo "selected";?>>Song</option>
					<option value='album' <?php if($_GET["type"]=="album") echo "selected";?>>Album</option>
					<option value='artist' <?php if($_GET["type"]=="artist") echo "selected";?>>Artist</option>
					<option value='friend' <?php if($_GET["type"]=="friend") echo "selected";?>>Friend</option>
				</select>
			</td>
			<td><input type='text' name='keyword' size='120' <?php if($_GET["keyword"]!=null) echo "value='".$_GET['keyword']."'";?>></td>
			<td><input type="submit" value=" Search "/> <a href="advancesearch.php">Advance search</a></td>
		</tr>
	</table>
</form>
