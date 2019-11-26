<?php
/*
Tests DLCCalculatePerformance class.
Creates several arrays in different formats
*/
require 'DLCCalculatePerformance.php';
$tests = array();
$tests[]=array(
  array('irr'=>18.2140,'pct'=>43.3735,'rate'=>12.7356),
  array('amount'=>-6,'date'=>"1/1/2001"),
  array('amount'=>-18,'date'=>"7/1/2002"),
  array('amount'=>-7,'date'=>"7/1/2001"),
  array('amount'=>100,'date'=>"1/1/2002"),
  array('amount'=>-16,'date'=>"1/1/2002"),
  array('amount'=>0,'date'=>"1/1/2002"),
  array('amount'=>-20,'date'=>"1/1/2003"),
  array('amount'=>-5,'date'=>"7/1/2000"),
  array('amount'=>100,'date'=>"1/1/2000"),
  array('amount'=>-200,'date'=>"1/1/2003"));
$tests[]=array(
  array('irr'=>10.0000,'pct'=>10.0000,'rate'=>9.9714),
  array('amount'=>100,'date'=>0),
  array('amount'=>-110,'date'=>365));

$tests[]=array(
  array('irr'=>0.6710,'pct'=>6.5117,'rate'=>.3510),
  array('amount'=>40000,'date'=>0),
  array('amount'=>-2350.49,'date'=>365),
  array('amount'=>40000,'date'=>'a'),
  array('amount'=>-2352.34,'date'=>730),
  array('amount'=>40000,'date'=>-1),
  array('amount'=>-2354.21,'date'=>1095),
  array('amount'=>-2356.10,'date'=>1460),
  array('amount'=>-2357.99,'date'=>1825),
  array('amount'=>-2359.91,'date'=>2190),
  array('amount'=>-2361.83,'date'=>2555),
  array('amount'=>-2363.78,'date'=>2920),
  array('amount'=>-2365.73,'date'=>3285),
  array('amount'=>-2367.71,'date'=>3650),
  array('amount'=>-2369.70,'date'=>4015),
  array('amount'=>-2371.70,'date'=>4380),
  array('amount'=>-2373.72,'date'=>4745),
  array('amount'=>-2375.75,'date'=>5110),
  array('amount'=>-2377.80,'date'=>5475),
  array('amount'=>-2379.87,'date'=>5840),
  array('amount'=>-2381.95,'date'=>6205),
  array('amount'=>-2384.09,'date'=>6570));
$tests[]=array(
  array('irr'=>3.6605,'pct'=>38.3762,'rate'=>1.8206),
  array('amount'=>5000,'date'=>0),
  array('amount'=>-382.52,'date'=>365),
  array('amount'=>-382.68,'date'=>730),
  array('amount'=>-382.87,'date'=>1095),
  array('amount'=>-383.06,'date'=>1460),
  array('amount'=>-383.27,'date'=>1825),
  array('amount'=>-383.47,'date'=>2190),
  array('amount'=>-383.68,'date'=>2555),
  array('amount'=>-383.91,'date'=>2920),
  array('amount'=>-384.13,'date'=>3285),
  array('amount'=>-384.37,'date'=>3650),
  array('amount'=>-384.62,'date'=>4015),
  array('amount'=>-384.88,'date'=>4380),
  array('amount'=>-385.15,'date'=>4745),
  array('amount'=>-385.42,'date'=>5110),
  array('amount'=>-385.71,'date'=>5475),
  array('amount'=>-386.01,'date'=>5840),
  array('amount'=>-386.32,'date'=>6205),
  array('amount'=>-386.74,'date'=>6570));
$tests[]=array(
  array('irr'=>0.6705,'pct'=>6.5117,'rate'=>.3508),
  array('amount'=>40000,'date'=>0),
  array('amount'=>-2350.49,'date'=>0),
  array('amount'=>-2352.34,'date'=>0),
  array('amount'=>-2354.21,'date'=>0),
  array('amount'=>-2356.10,'date'=>0),
  array('amount'=>-2357.99,'date'=>0),
  array('amount'=>-2359.91,'date'=>0),
  array('amount'=>-2361.83,'date'=>0),
  array('amount'=>-2363.78,'date'=>0),
  array('amount'=>-2365.73,'date'=>0),
  array('amount'=>-2367.71,'date'=>0),
  array('amount'=>-2369.70,'date'=>0),
  array('amount'=>-2371.70,'date'=>0),
  array('amount'=>-2373.72,'date'=>0),
  array('amount'=>-2375.75,'date'=>0),
  array('amount'=>-2377.80,'date'=>0),
  array('amount'=>-2379.87,'date'=>0),
  array('amount'=>-2381.95,'date'=>0),
  array('amount'=>-2384.09,'date'=>0));
$tests[]=array(
  array('irr'=>0.6705,'pct'=>6.5117,'rate'=>.3508),
  array('amount'=>40000,'date'=>"2000-1-1"),
  array('amount'=>-2350.49,'date'=>"2001-1-1"),
  array('amount'=>-2352.34,'date'=>"2002-1-1"),
  array('amount'=>-2354.21,'date'=>"2003-1-1"),
  array('amount'=>-2356.10,'date'=>"2004-1-1"),
  array('amount'=>-2357.99,'date'=>"2005-1-1"),
  array('amount'=>-2359.91,'date'=>"2006-1-1"),
  array('amount'=>-2361.83,'date'=>"2007-1-1"),
  array('amount'=>-2363.78,'date'=>"2008-1-1"),
  array('amount'=>-2365.73,'date'=>"2009-1-1"),
  array('amount'=>-2367.71,'date'=>"2010-1-1"),
  array('amount'=>-2369.70,'date'=>"2011-1-1"),
  array('amount'=>-2371.70,'date'=>"2012-1-1"),
  array('amount'=>-2373.72,'date'=>"2013-1-1"),
  array('amount'=>-2375.75,'date'=>"2014-1-1"),
  array('amount'=>-2377.80,'date'=>"2015-1-1"),
  array('amount'=>-2379.87,'date'=>"2016-1-1"),
  array('amount'=>-2381.95,'date'=>"2017-1-1"),
  array('amount'=>-2384.09,'date'=>"2018-1-1"));
// Real world, my portfolio: CCI 5/6/16-10/23/19
$tests[]=array(
  array('irr'=>19.9076,'pct'=>32.9502,'rate'=>8.6287),
  array('amount'=>10582.59,'date'=>100),
  array('amount'=>-106.20,'date'=>145),
  array('amount'=>106.20,'date'=>145),
  array('amount'=>-107.17,'date'=>237),
  array('amount'=>107.17,'date'=>237),
  array('amount'=>-116.11,'date'=>328),
  array('amount'=>116.11,'date'=>328),
  array('amount'=>-117.37,'date'=>419),
  array('amount'=>117.37,'date'=>419),
  array('amount'=>-118.55,'date'=>510),
  array('amount'=>118.55,'date'=>510),
  array('amount'=>-119.65,'date'=>601),
  array('amount'=>119.65,'date'=>601),
  array('amount'=>-133.50,'date'=>692),
  array('amount'=>133.50,'date'=>692),
  array('amount'=>-134.78,'date'=>786),
  array('amount'=>134.78,'date'=>786),
  array('amount'=>-136.05,'date'=>874),
  array('amount'=>136.05,'date'=>874),
  array('amount'=>-137.39,'date'=>965),
  array('amount'=>137.39,'date'=>965),
  array('amount'=>-148.62,'date'=>1059),
  array('amount'=>148.62,'date'=>1059),
  array('amount'=>-150.20,'date'=>1147),
  array('amount'=>150.20,'date'=>1147),
  array('amount'=>12788.95,'date'=>1207),
  array('amount'=>9285.63,'date'=>1227),
  array('amount'=>-264.04,'date'=>1238),
  array('amount'=>264.04,'date'=>1238),
  array('amount'=>-343.93,'date'=>1332),
  array('amount'=>343.93,'date'=>1332),
  array('amount'=>-43417.77,'date'=>1355));

// Real world, my portfolio: PFE 3/2/16-1-/23/19
$tests[]=array(
  array('irr'=>9.9366,'pct'=>41.1850,'rate'=>9.9121),
  array('amount'=>156868.54,'date'=>100),
  array('amount'=>1579.21,'date'=>100),
  array('amount'=>-1579.21,'date'=>100),
  array('amount'=>1594.81,'date'=>191),
  array('amount'=>-1594.81,'date'=>191),
  array('amount'=>1608.68,'date'=>283),
  array('amount'=>-1608.68,'date'=>283),
  array('amount'=>1622.45,'date'=>374),
  array('amount'=>-1622.45,'date'=>374),
  array('amount'=>1747.04,'date'=>464),
  array('amount'=>-1747.04,'date'=>464),
  array('amount'=>1763.42,'date'=>556),
  array('amount'=>-1763.42,'date'=>556),
  array('amount'=>1780.94,'date'=>648),
  array('amount'=>-1780.94,'date'=>648),
  array('amount'=>1797.98,'date'=>739),
  array('amount'=>-1797.98,'date'=>739),
  array('amount'=>1927.27,'date'=>829),
  array('amount'=>-1927.27,'date'=>829),
  array('amount'=>1944.98,'date'=>921),
  array('amount'=>-1944.98,'date'=>921),
  array('amount'=>1963.29,'date'=>1016),
  array('amount'=>-1963.29,'date'=>1016),
  array('amount'=>1979.31,'date'=>1106),
  array('amount'=>-1979.31,'date'=>1106),
  array('amount'=>2111.49,'date'=>1194),
  array('amount'=>-2111.49,'date'=>1194),
  array('amount'=>2129.14,'date'=>1292),
  array('amount'=>-2129.14,'date'=>1292),
  array('amount'=>2147.12,'date'=>1380),
  array('amount'=>-2147.12,'date'=>1380),
  array('amount'=>-13031.50,'date'=>1395),
  array('amount'=>-208443.32,'date'=>1431));

// Real world, my portfolio: PFE 3/2/16-1-/23/19
$tests[]=array(
  array('irr'=>9.9366,'pct'=>41.1850,'rate'=>9.9121),
  array('amount'=>156868.54,'date'=>"3/2/16"),
  array('amount'=>1579.21,'date'=>"3/2/16"),
  array('amount'=>-1579.21,'date'=>"3/2/16"),
  array('amount'=>1594.81,'date'=>"6/1/16"),
  array('amount'=>-1594.81,'date'=>"6/1/16"),
  array('amount'=>1608.68,'date'=>"9/1/16"),
  array('amount'=>-1608.68,'date'=>"9/1/16"),
  array('amount'=>1622.45,'date'=>"12/1/16"),
  array('amount'=>100000,'date'=>"3/32/16"),
  array('amount'=>-1622.45,'date'=>"12/1/16"),
  array('amount'=>1747.04,'date'=>"3/1/17"),
  array('amount'=>-1747.04,'date'=>"3/1/17"),
  array('amount'=>1763.42,'date'=>"6/1/17"),
  array('amount'=>-1763.42,'date'=>"6/1/17"),
  array('amount'=>1780.94,'date'=>"9/1/17"),
  array('amount'=>-1780.94,'date'=>"9/1/17"),
  array('amount'=>1797.98,'date'=>"12/1/17"),
  array('amount'=>-1797.98,'date'=>"12/1/17"),
  array('amount'=>1927.27,'date'=>"3/1/18"),
  array('amount'=>-1927.27,'date'=>"3/1/18"),
  array('amount'=>1944.98,'date'=>"6/1/18"),
  array('amount'=>-1944.98,'date'=>"6/1/18"),
  array('amount'=>1963.29,'date'=>"9/4/18"),
  array('amount'=>-1963.29,'date'=>"9/4/18"),
  array('amount'=>1979.31,'date'=>"12/3/18"),
  array('amount'=>-1979.31,'date'=>"12/3/18"),
  array('amount'=>2111.49,'date'=>"3/1/19"),
  array('amount'=>-2111.49,'date'=>"3/1/19"),
  array('amount'=>2129.14,'date'=>"6/7/19"),
  array('amount'=>-2129.14,'date'=>"6/7/19"),
  array('amount'=>2147.12,'date'=>"9/3/19"),
  array('amount'=>-2147.12,'date'=>"9/3/19"),
  array('amount'=>-13031.50,'date'=>"9/18/19"),
  array('amount'=>-208443.32,'date'=>"10/24/19"));

  $style_green = " style='color:green;' ";
  $style_red = " style='color:red;' ";
  for ($ix=0;$ix<count($tests);$ix++)
  {
    $test = $tests[$ix];
    $vals = $test[0];
    extract($vals);
    $irr = sprintf("%.04f", $irr);
    $rate = sprintf("%.04f", $rate);
    $pct = sprintf("%.04f", $pct);
    $flows = array();
    for ($jx=1;$jx<count($test);$jx++)
    {
      $flows[] = array($test[$jx]['date'],$test[$jx]['amount']);
    }
    echo "<div style='border:1px solid black'>" .
         "<b>Expected Values</b>" .
         "<br>IRR: " . $irr . 
         "<br>Pct: " . $pct . 
         "<br>Rate: " . $rate . 
         "<br>";
    $perf = new DLCCalculatePerformance($flows);
    $days_calc = $perf->GetMaxDays();
    $in_calc = sprintf("%.02f", $perf->CalculateInputAmount());
    $out_calc = sprintf("%.02f", $perf->CalculateOutputAmount());
    $adj_calc = sprintf("%.02f", $perf->CalculateInputAmountAdjustment());
    $ret_calc = sprintf("%.02f", $out_calc - $in_calc);
    $out_minus_in_calc = sprintf("%.02f", $out_calc - $in_calc);
    $in_minus_adj_calc = sprintf("%.02f", $in_calc - $adj_calc);
    $irr_calc = sprintf("%.04f", $perf->CalculateIRR());
    $pct_calc = sprintf("%.04f", $perf->CalculatePerformance());
    $rate_calc = sprintf("%.04f", $perf->CalculatePerformanceRate());
    ($irr_calc == $irr)?$style_irr=$style_green:$style_irr=$style_red;
    ($pct_calc == $pct)?$style_pct=$style_green:$style_pct=$style_red;
    ($rate_calc == $rate)?$style_rate=$style_green:$style_rate=$style_red;
    echo "<b>Calculated Values</b>" .
         "<br><span $style_irr>IRR: " . $irr_calc . "</span>" . 
         "<br><span $style_pct>Pct: " . $pct_calc . "</span>" . 
         "<br><span $style_rate>Rate: " . $rate_calc . "</span>" .
         "<br>Days: " . $days_calc . 
         "<br>Input Amount: " . $in_calc . 
         "<br>Output Amount: " . $out_calc . 
         "<br>Adjustment for Reinvestment: " . $adj_calc .
         "<br>Return: " . $ret_calc . 
         "<br>Output - Input: " . $out_minus_in_calc . 
         "<br>Input - Adjustment: " . $in_minus_adj_calc . 
         "<br>";
    echo "</div><br>";
}
?>

