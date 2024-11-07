<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\Barber;
use App\Models\Walkin;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\Product;
use App\Models\BarberCommission;
use App\Models\Customer;
use App\Models\ProductSold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $previousMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $startDate = $request->input('start_date', $previousMonth);
        $endDate = $request->input('end_date', $currentDate);
        $uniqueIds = [];
        $start = Carbon::create($startDate);
        $end = Carbon::create($endDate);
        $monthNames = [];
        while ($start->lte($end)) {
            // Add the month name to the array
            $monthNames[] = $start->format('m');
            $year = $start->format('Y');
            // Move to the next month
            $start->addMonth();
        }
        $totalIncome = 0;
        $totalSold=0;
        $totalExpense = 0;
        $forCommission=0;
        $totalCommission=0;
        // Fetch data based on the date range
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();
        $productsolds = ProductSold::whereBetween('date', [$startDate, $endDate])->get();
        $barbers = Barber::all();
        foreach($barbers as $barber){
            $bookings = Booking::whereBetween('date', [$startDate, $endDate])->where('status', 'complete')->where('barber_id',$barber->uniqueid)->get();
            $walkins = Walkin::whereBetween('date', [$startDate, $endDate])->where('status', 'complete')->where('barber_id',$barber->uniqueid)->get();
            $commissions = [];
            foreach($monthNames as $month){
                $commission_rate_filter = BarberCommission::
                                            where('barber_id',$barber->id)->
                                            whereYear('date','=',$year)
                                            ->whereMonth('date','=',$month)
                                            ->get();
                if(count($commission_rate_filter) !== 0){
                    foreach($commission_rate_filter as $data){
                        array_push($commissions,(float)$data->commission_rate);
                    }
                }else{
                    array_push($commissions,$barber->commission_rate);
                }
            }

            $commissionAverage = array_sum($commissions) / count($commissions);
            $totalNoDiscount = 0;
	    $totalIncomeUnique = collect([...$bookings, ...$walkins])->sum(function ($item)  use (&$uniqueIds, &$forCommission, &$totalIncome, &$totalCommission, &$barber, &$totalNoDiscount){
            	$Uniqueincome = $item->amount;
	    	$totalNoDiscount += $Uniqueincome;
                $forCommission += $Uniqueincome;
                if ($item->discount_type === 'shop' && !in_array($item->uniqueid, $uniqueIds)) {
                    $uniqueIds[] = $item->uniqueid;
                    $Uniqueincome -= $item->discount;
                }
                return $Uniqueincome;
            });

            // Calculate total Commission Pay
            $commissionUnique = $totalNoDiscount * ($commissionAverage / 100);
            $totalCommission += $commissionUnique;
            $totalIncome += $totalIncomeUnique;
        }
        // Process product solds
        $totalSold = $productsolds->sum('total_amount');
        // Process expenses
        $totalExpense = $expenses->sum('amount');
        $totalProfit = ($totalIncome + $totalSold) - $totalExpense;
        // Get Products
        $recent_products = Product::limit(5)->get();
        // Load your view with data
        return view('dashboard', [
            'totalSold'=> $totalSold,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpense,
            'totalProfit' => $totalProfit,
            'totalCommission' => $totalCommission,
            'recent_products'=> $recent_products,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function chartValue()
    {
        $currentYear = date('Y');
        $value = array();
        for ($month = 1; $month <= 12; $month++) {
            if($month == 1 && $currentYear == 2024){
                $totalIncome = 970000;
            }elseif($month == 2 && $currentYear == 2024) {
                $totalIncome = 572500;

            }else{
                $totalIncome = 0;
            }
            // $totalIncome = 0;
            $uniqueIds = [];
            $totalDiscount = 0;

            $bookings = Booking::where('status', 'complete')->whereMonth('date', $month)->whereYear('date', date('Y'))->get();

            foreach ($bookings as $booking) {
                if ($booking->discount_type === 'shop' && !in_array($booking->uniqueid, $uniqueIds)) {
                    $uniqueIds[] = $booking->uniqueid;
                    $totalDiscount += $booking->discount;
                }
                $totalIncome += $booking->amount;
            }

            $totalIncome -= $totalDiscount;

            //--------

            $totalDis = 0;
            $walkinsUniqueIds = [];
            $walkins = Walkin::where('status', 'complete')->whereMonth('walk_in_customers.date', $month)->whereYear('walk_in_customers.date', date('Y'))->get();

            foreach ($walkins as $walkin) {
                if ($walkin->discount_type === 'shop' && !in_array($walkin->uniqueid, $walkinsUniqueIds)) {
                    $walkinsUniqueIds[] = $walkin->uniqueid;
                    $totalDis += $walkin->discount;
                }
                $totalIncome += $walkin->amount;
            }

            $totalIncome -= $totalDis;

            $productSolds = ProductSold::whereMonth('date', $month)->whereYear('date', date('Y'))->get();
            $totalIncome += $productSolds->sum('total_amount');

            array_push($value, $totalIncome);
        }

        return response()->json($value);
    }

}

