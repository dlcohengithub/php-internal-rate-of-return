<?php
/*
 * Dave Cohen
 *   Version 0.1
 *   November 2019
 *   dave@alleycatsw.com
 *
 * Much of this code was actually converted from Clarion, in my Capital Gainz 
 * portfolio management program. So, at least the original was battle tested!
 *
 * Like and use this code? Send me a few bucks via Paypal at dave@alleycatsw.com
 * Thanks!
 *
*/
class DLCCalculatePerformance
{
   /*
   * Usage:
   *
   *   Each item in array of cash flows has date and amount.  
   *
   *     dates (first item): any representation of date  
   *       Automatically converts / to -   
   *       If no dates specified start at 2000-01-01 and add year to each one   
   *       If all 0s then start at 2000-01-01 and add year to each one   
   *       If no dashes and up to 7 digits, this is number of days beginning 2000-01-01   
   *       If dashes and first item 1 or 2 digits, this is mm-dd-yyyy or mm-dd-yy   
   *       If dashes and first item 4 digits, this is yyyy-mm-dd   
   *       Dates do not have to be in order, they will be sorted   
   *       Any invalid dates will be ignored   
   *
   *     amounts (second item) is positive for purchases, negative for distributions and sales   
   *      Any sale/distribution followed by purchase treated as reinvestment   
   *      Any bad data, including 0 amounts, will be ignored   
   *   
   *   To calculate performance after investment has been purchased, just specify a purchase on the    
   *   desired beginning date using the value of the investment on that date.   
   *   For instance, if you already owned 100 shares of stock XYZ on 1/1/2000, when it was trading at    
   *   $10 per share, but wanted to calculate performance beginning on that date, specify cash flow of    
   *   1000.00 (positive, purchase) on 2000-01-01.    
   *   
   *   To calculate peformance while investment is still being held, just specify a sale on the desired   
   *   end date using the value of the investment on that date.   
   *   For instance, if you still held stock XYZ on 12/31/2000, which it was trading at $10 per share,   
   *   but wanted to calculate performance as of that date, specify a cash flow of -1000.00    
   *   (negative, sale) on 2000-12-31.   
   *   
   *   Thus, if you had the following cash flows:   
   *    12/01/1999 $1000 purchase (100 shares at $10)   
   *    01/01/2000    $5 dividend   
   *    01/01/2000    $5 reinvestment (bought .5 shares)   
   *    04/01/2000    $5 dividend   
   *    04/01/2000    $5 reinvestment (bought .5 shares)    
   *    07/01/2000    $5 dividend   
   *    07/01/2000    $5 reinvestment (bought .5 shares)    
   *    10/01/2000    $5 dividend   
   *    10/01/2000    $5 reinvestment (bought .5 shares)    
   *    12/31/2000    (still held 100 shares but now at $11)   
   *   The cash flow array would be:   
   *   $flows = array(array('date'=>'12/1/1999', 'amount'=>1000),    
   *                  array('date'=>'1/1/2000', 'amount'=>-5), array('date'=>'1/1/2000', 'amount'=>5),      
   *                  array('date'=>'4/1/2000', 'amount'=>-5), array('date'=>'4/1/2000', 'amount'=>5),      
   *                  array('date'=>'7/1/2000', 'amount'=>-5), array('date'=>'7/1/2000', 'amount'=>5),      
   *                  array('date'=>'10/1/2000','amount'=>-5), array('date'=>'10/1/2000', 'amount'=>5),      
   *                  array('date'=>'12/31/2000', 'amount'=>-1100));     
   *    
   *    
   *   To create performance object:   
   *     Create array of cash flows:   
   *     $flows = array(array('date'=>date, 'amount'=>amount), array('date'=>date,'amount'=>amount) ...);   
   *     Instantiate object:   
   *     $perf = new DLCCalculatePerformance($flows);   
   *   To get internal rate of return:   
   *     $irr = $perf->CalculateIRR();   
   *   To get performance over the period:   
   *     $ret = $perf->CalculatePerformance();   
   *   To get annual performance without consideration of time:   
   *     $rate = $perf->CalculatePerformanceRate();   
   *   To get total input amount (purchases) of flows:   
   *     $input = $perf->CalculateInputAmount();   
   *   To get total output amount (sales/distributions) of flows:   
   *     $output = $perf->CalculateOutputAmount();   
   *   To get input amount adjustments for reinvestments:   
   *     $adj = $perf->CalculateInputAmountAdjustment();   
   *   To get the number of days for the cash flows:   
   *     $days = $perf->GetMaxDays();   
   */
  private $Flows = array(); 
  private $FirstDay = 0;  // First cash flow number of days, always 0.
  private $LastDay = 0;   // Last cash flow number of days.
  private $InputAmount = 0;  // Gross input
  private $OutputAmount = 0; // Gross output
  private $InputAmountAdjustment = 0; // Reinvested distributions and sales. 

  public function __construct($flows=array())
  /*
   * Build associative array from data specified in constructor:
   *   array of dates, amounts
   * The constructed associative array is:
   *   date
   *   amount
   *   days
   * Also cleans up data.
  */
  {
    if (!isset($flows[0]) || !isset($flows[0][0]) || !isset($flows[0][1])) return;
    if (!count($flows)) return;

    $flows = self::MakeAssociativeArray($flows);
    if (!count($flows)) return;

    $flows = self::FixFlowData($flows); // Clean up data
    if (!count($flows)) return;

    // Sort by date increasing, amount increasing    
    usort($flows,'self::SortFlows');

    // Now set number of days held
    $first_date = date_create($flows[0]['date']);
    $flows[0]['days'] = 0; // start at 0
    // At this point all dates are YYYY-MM-DD, so calculate days
    for($ix = 1; $ix < count($flows); $ix+=1)
    {
      $this_date = date_create($flows[$ix]['date']); 
      $num_days = date_diff($first_date,$this_date);
      $flows[$ix]['days'] = $num_days->format("%a");
    }

    // Assume any distributions, sales - negative cash flows -
    // are reinvested, so get better calculation of standard performance.
    // For IRR, it doesn't matter.
    $amt_in = 0;
    $amt_out = 0;
    $amt_adj = 0;
    for($ix = 0; $ix < count($flows); $ix+=1)
    {
      $this_flow = $flows[$ix]['amount'];
      if ($this_flow > 0) // purchase
      {
        $this->InputAmount += $this_flow;
        $amt_in += $this_flow;
        if ($amt_out > 0)
        {
          // How much of this input is reinvested from output?
          if ($amt_out >= $amt_in) // All input so far is reinvested from output
          {
            $this_adj = $amt_in;
            $amt_out -= $this_adj;
            $amt_in = 0;
          }
          else // Some input is reinvested from output
         {
            $this_adj = $amt_out;
            $amt_in -= $this_adj;
            $amt_out = 0;
          }
          $amt_adj += $this_adj;
        }
      }
      else // distribution or sale
      {
        $amt_out += abs($this_flow);
        $this->OutputAmount += abs($this_flow);
      }
    }
    $this->InputAmountAdjustment = $amt_adj;
    if (count($flows))
    {
      $this->Flows = $flows;
      $this->FirstDay = $flows[0]['days']; // Should always be 0
      $this->LastDay = $flows[count($flows)-1]['days'];
    }
  }

  private static function MakeAssociativeArray($flows)
  /*
   * Make associative array from input data.
   * Input data is array of date, amount pairs.
   * Associative array is:
   *  date
   *  amount
   *  days
  */
  {
    $fix_flows = array();
    for($ix=0;$ix<count($flows);$ix++)
    {
      $fix_flows[] = array('date'=>$flows[$ix][0],'amount'=>$flows[$ix][1],'days'=>0);
    }
    $flows = $fix_flows;
    return $fix_flows;
  }

  private static function AreAllDatesZero($flows)
  /*
   * Determine if all of the cash flow dates are 0.
  */
  {
    for($ix = 0; $ix < count($flows); $ix+=1)
    {
      $this_date = trim($flows[$ix]['date']);
      if ($this_date) return 0;
    }
    return 1;
  }

  private static function AreAllDatesNumbers($flows)
  /*
   * Determine if all of the cash flow dates are numbers.
  */
  {
    // Number of days, but must be less than 9999999 to distinguish from yyyymmdd
    for($ix = 0; $ix < count($flows); $ix+=1)
    {
      $this_date = trim($flows[$ix]['date']);
      if (!preg_match('/^[0-9]{1,7}$/', $this_date)) return 0;
    }
    return 1;
  }

  private static function FixFlowData($flows)
  /*
   * Clean up cash flows:
   *   convert / to -
   *   strip out 0 or invalid amounts
   *   strip out dates with invalid characters
   *   if all dates are 0, set dates starting at 2000-01-01 and add one year to each
   *   if all dates are numbers, set to 2000-01-01 plus the number of days
   *   check for valid date formats and valid dates - remove any invalid ones
  */
  {
    // Strip out 0 amounts, not number amounts, dates with invalid characters
    $fix_flows = array();
    for($ix = 0; $ix < count($flows); $ix+=1)
    {
      $ok = 1;
      $this_amount = trim($flows[$ix]['amount']);
      $this_date = trim($flows[$ix]['date']);
      $this_date = str_replace('/','-',$this_date); // convert / to -
      if (!is_numeric($this_amount))
        $ok = 0;
      else if ($this_amount == 0) 
        $ok = 0;
      else if (!preg_match('/^[0-9\-]+$/', $this_date)) // only numbers and dashes
        $ok = 0;
      else if ((strpos($this_date,'-')!==false) && (strlen($this_date)<6)) // if dashes, at least 8 chars (m-d-yy)
        $ok = 0;
      if ($ok)
      {
        $flows[$ix]['date'] = $this_date;
        $flows[$ix]['amount'] = $this_amount;
        $flows[$ix]['days'] = 0;
        $fix_flows[] = $flows[$ix];
      }
    }
    $flows = $fix_flows;

    if (self::AreAllDatesZero($flows)) 
    // All dates are 0, so start at 2000-01-01 and add 1 year to each
    {
      for($ix = 0; $ix < count($flows); $ix+=1)
      {
        $flows[$ix]['date'] = (2000 + $ix) . "-01-01"; // 2000-01-01, 2000-01-01, etc
      }
      return $flows;
    }

    if (self::AreAllDatesNumbers($flows)) 
    // All dates are number of days, so add days to 2000-01-01
    {
      // 2000-01-01 good a place as any to start!
      $start_date = date_create("2000-01-01");
      for($ix = 0; $ix < count($flows); $ix+=1)
      {
        $date = clone($start_date); // need new copy so not change start_date!
        date_add($date,date_interval_create_from_date_string($flows[$ix]['date'] . " days"));
        $flows[$ix]['date'] =  date_format($date,"Y-m-d");
      }
      return $flows;
    }
    // Strip out bad dates
    $fix_flows = array();
    for($ix = 0; $ix < count($flows); $ix+=1)
    {
      $this_amount = trim($flows[$ix]['amount']);
      $this_date = trim($flows[$ix]['date']);
      $this_day = '';
      // check for bad dates
      if (preg_match('/^[0-9]{8}$/', $this_date)) // yyyymmdd
      {
        $this_year = substr($this_date,0,4); 
        $this_month = substr($this_date,4,2); 
        $this_day = substr($this_date,6,2); 
      }
      else if (preg_match('/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/', $this_date)) // yyyy-mm-dd
      {
        $parts = explode('-',$this_date);
        $this_year = $parts[0];
        $this_month = $parts[1];
        $this_day = $parts[2];
      }
      else if (preg_match('/^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{2,4}$/', $this_date)) // mm-dd-yyyy
      {
        $parts = explode('-',$this_date);
        $this_year = $parts[2];
        $this_month = $parts[0];
        $this_day = $parts[1];
      }
      if ($this_day)
      {
        if (checkdate($this_month,$this_day,$this_year))
        // want 2 digit months and days, 4 digit year
        {
          if (strlen($this_day) < 2) $this_day = '0' . $this_day;
          if (strlen($this_month) < 2) $this_month = '0' . $this_month;
          if (strlen($this_year) < 4) $this_year = "20" . $this_year;
          $this_date = $this_year . '-' . $this_month . '-' . $this_day;
          $fix_flows[] = array('date'=>$this_date,'amount'=>$this_amount, 'days'=>0);
        }
      }
    }
    return $fix_flows;
  }

  public function GetMaxDays()
  /*
   * Return maximum number of days, whish is days between first and last cash flows.
  */
  {
    // Add 1, so if buy then sell on same day, it is held for 1 day.
    // Basically, this means first day assumes cash flow at start of day and 
    //                       last day assumes cash flow at end of day.
    return $this->LastDay - $this->FirstDay + 1;
  }

  private static function SortFlows($a,$b)
  {
    // Sort by days, then increasing amount (sale/distributions negative, so are first)
    // This is so we can determine reinvestment
    if ($a['date'] < $b['date']) return -1;
    if ($a['date'] > $b['date']) return 1;
    if ($a['amount'] < $b['amount']) return -1;
    if ($a['amount'] > $b['amount']) return 1;
    return 0;
  }

  public function CalculateInputAmount()
  /*
   * Calculate total input amount, which are positive cash flows, or purchases.
   * This was already set in constructor.
  */
  {
    if (!count($this->Flows)) return 0;
    return round($this->InputAmount,2);
  }

  public function CalculateInputAmountAdjustment()
  /*
   * Calculate input adjustment amount, which is sales/distributions reinvested in subsequent purchases.
   * This was already set in constructor.
  */
  {
    if (!count($this->Flows)) return 0;
    return round($this->InputAmountAdjustment,2);
  }

  public function CalculateOutputAmount()
  /*
   * Calculate output amount, which are negative cash flows, or sales/distributions.
   * This was already set in constructor.
  */
  {
    if (!count($this->Flows)) return 0;
    return round($this->OutputAmount,2);
  }

  public function CalculateIRR()
  /*
   * Calculate the internal rate of return for the specified cash flows.
  */
  {
    if (!count($this->Flows)) return 0;
    $limit = array(0,1.1);
    for(;;)
    {
      $rate = ($limit[1] + $limit[0])/2;
      $test_tot = 0;
      for($ix = 0; $ix < count($this->Flows); $ix+=1)
      {
        $rate_pow = $this->LastDay - $this->Flows[$ix]['days'] + 1;
        $rate_res = pow($rate, $rate_pow);
        $test_tot += $this->Flows[$ix]['amount'] * $rate_res;
      }
      $test_tot >= 0?$which = 1:$which = 0;
      if (($limit[0] >= $limit[1]) || ($limit[$which] == $rate))
      {
        $rate = 100 * ((pow($rate,365)) - 1.0);
        break;
      }
      $limit[$which] = $rate;
    }
    return round($rate,4);
  }

  public function CalculateIRRPct()
  /*
   * Calculate the internal rate of return over the entire date span.
   * This is not really useful, CalculatePerformance is much better indicator.
  */
  {
    if (!count($this->Flows)) return 0;
    $irr = self::CalculateIRR();
    $yr_frac = self::GetMaxDays()/365;
    $pct = 100 * pow(1+($irr/100),$yr_frac);
    return round($pct,4);
  }

  public function CalculatePerformance()
  /*
   * Calculate performance from first to last cash flow, regardless of time value.
  */
  {
    if (!count($this->Flows)) return 0;
    $pct = 0;
    if ($this->InputAmount == $this->InputAmountAdjustment) return "Infinite";
    $pct = 100 * (($this->OutputAmount - $this->InputAmount)/($this->InputAmount - $this->InputAmountAdjustment));
    return round($pct,4);
  }
            
  public function CalculatePerformanceRate()
  /*
   * Calculate annual performance using performance from first to last cash flow.
   * Internal rate of return is much better for this.
   * Over a one year period, this value will be close to IRR.
  */
  {
    if (!count($this->Flows)) return 0;
    $pct = 0;
    $yr_frac = self::GetMaxDays()/365;
    if ($this->InputAmount == $this->InputAmountAdjustment) return "Infinite";
    $pct = 100 * (pow(($this->OutputAmount - $this->InputAmountAdjustment)/($this->InputAmount - $this->InputAmountAdjustment),1/$yr_frac)-1);
    return round($pct,4);
  }
}
?>
