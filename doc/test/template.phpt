<?php
return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>UnitTestResultViewer < cmTools</title>
    <link rel="stylesheet" href="base.css"/>
    <link rel="stylesheet" href="style.css"/>
    <script src="//js.ceusmedia.com/jquery/1.2.3.pack.js"/></script>
  </head>
  <body>
    <div id="tables">
      <h2>UnitTest Results</h2>
      <p>Date: '.$date.'</p>
      <p>Time: '.$time.'</p>
      <br/>

      <table class="grid" width="300px">
        <caption>Results</caption>
        <colgroup>
          <col width="140px"/>
          <col width="80px"/>
          <col width="80px"/>
        </colgroup>
        <tr>
          <th></th>
          <th>Count</th>
          <th>Ratio</th>
        </tr>
        <tr>
          <td>Test Suites</td>
          <td>'.$countSuites.'</td>
          <td></td>
        </tr>
        <tr>
          <td>Test Cases</td>
          <td>'.$countTests.'</td>
          <td>100%</td>
        </tr>
        <tr>
          <td><b style="color: green">Passed</b></td>
          <td><b style="color: green">'.$countPassed.'</b></td>
          <td><b style="color: green">'.$ratioPassed.'%</b></td>
        </tr>
        <tr>
          <td>Failures</td>
          <td>'.$countFailures.'</td>
          <td>'.$ratioFailures.'%</td>
        </tr>
        <tr>
          <td>Errors</td>
          <td>'.$countErrors.'</td>
          <td>'.$ratioErrors.'%</td>
        </tr>
      </table>
    <br/>
    </div>
    <div id="messages">
      <div class="list" id="listFailures">
        <h3>Failures</h3>
        '.$failures.'
      </div>
      <br/>
      <div class="list" id="listErrors">
        <h3>Errors</h3>
        '.$errors.'
      </div>
    </div>
  </body>
</html>';
?>