# php-internal-rate-of-return
PHP class to calculate internal rate of return from input array of cash flow dates and amounts.

November 2019
Version 0.1

Dave Cohen
dave@alleycatsw.com

Also calculates non-time weighted performance and annual rate based on that.

Flexible format for object creation.

Easy to use.

 
    Usage:
      Each item in array of cash flows has date and amount.
        dates (first item): any representation of date
          Automatically converts / to -
          If no dates specified start at 2000-01-01 and add year to each one
          If all 0s then start at 2000-01-01 and add year to each one
          If no dashes and up to 7 digits, this is number of days beginning 2000-01-01
          If dashes and first item 1 or 2 digits, this is mm-dd-yyyy or mm-dd-yy
          If dashes and first item 4 digits, this is yyyy-mm-dd
          Dates do not have to be in order, they will be sorted
          Any invalid dates will be ignores
        amounts (second item) is positive for purchases, negative for distributions and sales
         Any sale/distribution followed by purchase treated as reinvestment
         Any bad data, including 0 amounts, will be ignored
   
      To create performance object:
        Create array of cash flows:
        $flows = array(array('date'=>date, 'amount'=>amount), array('date'=>date,'amount'=>amount) ...);
        Instantiate object:
        $perf = new DLCCalculatePerformance($flows);
      To get internal rate of return:
        $irr = $perf->CalculateIRR();
      To get performance over the period:
        $ret = $perf->CalculatePerformance();
      To get annual performance without consideration of time:
        $rate = $perf->CalculatePerformanceRate();
      To get total input amount (purchases) of flows:
        $input = $perf->CalculateInputAmount();
      To get total output amount (sales/distributions) of flows:
        $output = $perf->CalculateOutputAmount();
      To get input amount adjustments for reinvestments:
        $adj = $perf->CalculateInputAmountAdjustment();
      To get the number of days for the cash flows:
        $days = $perf->GetMaxDays();
   
