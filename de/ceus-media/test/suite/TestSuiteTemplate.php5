<?php
/**
 *	@package	test
 *	@subpackage	suite
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
 $code = "
<style>
	table.list	{border: 1px solid #7F7F7F; width: 600px}
	th		{background: #DFDFDF; text-align: left}
	td.topic	{background: #EFEFEF; padding-left: 10px; }
	td.pos	{background: #7FFF7F; padding-left: 10px; text-align: left}
	td.neg	{background: #FF7F7F; padding-left: 10px; text-align: left}
</style>
<table class='list' border='0' cellspacing='0' cellspacing='0'>
  <colgroup>
    <col width='30%'/>
    <col width='35%'/>
    <col width='35%'/>
  </colgroup>
  <tr><td colspan='3' onClick='document.location.reload ();'>
    <table border='0' cellspacing='0' cellspacing='0' width='100%'>
      <colgroup>
        <col width='25%'/>
        <col width='25%'/>
        <col width='25%'/>
        <col width='25%'/>
      </colgroup>
      <tr><td colspan='6'><b>TestSuite </b> <em style='font-size: 1.5em'>".$class."</em></td></tr>
      <tr><td>date</td>	<td>".$date."</td>	<td>passed tests</td>	<td>".$counter."</td></tr>
      <tr><td>time</td>		<td>".$time."</td>	<td>result</td>		<td class='".$resultclass."'>".$result."</td></tr>
      <tr></tr>
    </table>
  </td></tr>
  ".$code ."
</table>";
?>