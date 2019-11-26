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
      Any invalid dates will be ignored   

    amounts (second item) is positive for purchases, negative for distributions and sales   
     Any sale/distribution followed by purchase treated as reinvestment   
     Any bad data, including 0 amounts, will be ignored   
  
  To calculate performance after investment has been purchased, just specify a purchase on the    
  desired beginning date using the value of the investment on that date.   
  For instance, if you already owned 100 shares of stock XYZ on 1/1/2000, when it was trading at    
  $10 per share, but wanted to calculate performance beginning on that date, specify cash flow of    
  1000.00 (positive, purchase) on 2000-01-01.    
  
  To calculate peformance while investment is still being held, just specify a sale on the desired   
  end date using the value of the investment on that date.   
  For instance, if you still held stock XYZ on 12/31/2000, which it was trading at $10 per share,   
  but wanted to calculate performance as of that date, specify a cash flow of -1000.00    
  (negative, sale) on 2000-12-31.   
  
  Thus, if you had the following cash flows:   
   12/01/1999 $1000 purchase (100 shares at $10)   
   01/01/2000    $5 dividend   
   01/01/2000    $5 reinvestment (bought .5 shares)   
   04/01/2000    $5 dividend   
   04/01/2000    $5 reinvestment (bought .5 shares)    
   07/01/2000    $5 dividend   
   07/01/2000    $5 reinvestment (bought .5 shares)    
   10/01/2000    $5 dividend   
   10/01/2000    $5 reinvestment (bought .5 shares)    
   12/31/2000    (still held 100 shares but now at $11)   
  The cash flow array would be:   
  $flows = array(array('date'=>'12/1/1999', 'amount'=>1000),    
                 array('date'=>'1/1/2000', 'amount'=>-5), array('date'=>'1/1/2000', 'amount'=>5),      
                 array('date'=>'4/1/2000', 'amount'=>-5), array('date'=>'4/1/2000', 'amount'=>5),      
                 array('date'=>'7/1/2000', 'amount'=>-5), array('date'=>'7/1/2000', 'amount'=>5),      
                 array('date'=>'10/1/2000','amount'=>-5), array('date'=>'10/1/2000', 'amount'=>5),      
                 array('date'=>'12/31/2000', 'amount'=>-1100));     
   
   
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

 

