<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\Product;
use App\Models\ProductSold;
use App\Models\Walkin;
use App\Models\Service;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarberCommission;


class ReportController extends Controller
{
    // Salary

public function salaryReport(Request $request) {
        $current_date = Carbon::now()->format('Y-m-d');
        $previous_month = Carbon::now()->startOfMonth()->format('Y-m');

        $start_date = Carbon::parse($request->input('start_date'));

        $month = $start_date->format('m');

        $barbers = Barber::get();
        $barberData = [];
        $barberunique = [];

        foreach ($barbers as $barber) {
            $commissions = BarberCommission::where('barber_id',$barber->id)
                                            ->whereMonth('date','=',$month)
                                            ->get();

            if(count($commissions) == 0){
                $appointments = Booking::where('barber_id', $barber->uniqueid)
                    ->whereMonth('date','=',$month)
                    ->where('status', 'complete')
                    ->get();

                $walkins = Walkin::where('barber_id', $barber->uniqueid)
                    ->whereMonth('date','=',$month)
                    ->get();

                    $totalServices = $appointments->count() + $walkins->count();
                    $totalEarned = 0;

                    foreach ($appointments as $appointment) {
                        if ($appointment->service) {
                            $totalEarned += $appointment->amount;
                        }
                    }

                    foreach ($walkins as $walkin) {
                        if ($walkin->service) {
                            $totalEarned += $walkin->amount;
                        }
                    }

                    $appointmentsDiscount = $appointments->filter(function ($appointment) {
                        return $appointment->discount_type === 'barber';
                    })->sum('discount');

                    $walkinsDiscount = $walkins->filter(function ($walkin) {
                        return $walkin->discount_type === 'barber';
                    })->sum('discount');

                    $totalDiscount = $appointmentsDiscount + $walkinsDiscount;

                    $totalCommission = ($totalEarned) * ($barber->commission_rate / 100);
                    if($totalServices != 0 && $totalEarned != 0){
                        $barberunique[] = [
                            'barber' => $barber,
                            'totalServices' => $totalServices,
                            'totalEarned' => $totalEarned,
                            'totalDiscount' => $totalDiscount,
                            'commissionRate' => $barber->commission_rate,
                            'totalCommission' => $totalCommission,
                            'status' => 'unconfirm',
                            'periodMonth' => $start_date,
                            // 'periodEnd' => $period['end'],
                        ];
                    }

            }else{
                foreach ($commissions as $index => $commission) {
                    $appointments = Booking::where('barber_id', $barber->uniqueid)
                    ->whereMonth('date','=',$month)
                    ->where('status', 'complete')
                    ->get();

                $walkins = Walkin::where('barber_id', $barber->uniqueid)
                    ->whereMonth('date','=',$month)
                    ->get();

                    $totalServices = $appointments->count() + $walkins->count();
                    $totalEarned = 0;

                    foreach ($appointments as $appointment) {
                        if ($appointment->service) {
                            $totalEarned += $appointment->amount;
                        }
                    }

                    foreach ($walkins as $walkin) {
                        if ($walkin->service) {
                            $totalEarned += $walkin->amount;
                        }
                    }

                    $appointmentsDiscount = $appointments->filter(function ($appointment) {
                        return $appointment->discount_type === 'barber';
                    })->sum('discount');

                    $walkinsDiscount = $walkins->filter(function ($walkin) {
                        return $walkin->discount_type === 'barber';
                    })->sum('discount');

                    $totalDiscount = $appointmentsDiscount + $walkinsDiscount;

                    $totalCommission = ($totalEarned) * ($commission->commission_rate / 100);
                    if($totalServices != 0 && $totalEarned != 0){
                        $barberunique[] = [
                            'barber' => $barber,
                            'totalServices' => $totalServices,
                            'totalEarned' => $totalEarned,
                            'totalDiscount' => $totalDiscount,
                            'commissionRate' => $commission->commission_rate,
                            'totalCommission' => $totalCommission,
                            'status' => 'confirm',
                            'periodMonth' => $start_date,
                            // 'periodEnd' => $period['end'],
                        ];
                    }
                }

            }

        }






            // if(count($commissions) == 0){
            //     $periods[] = [
            //         'month' => $start_date,
            //         'rate' => $barber->commission_rate,
            //         'status' => "unconfirm",
            //     ];
            // }else{

            //     foreach ($commissions as $index => $commission) {

            //         $month = Carbon::parse($commission->date);
            //         $periods[] = [
            //             'month' => $month,
            //             'rate' => $commission->commission_rate,
            //             'status' => "confirm",
            //         ];
            //     }
            // }



            // foreach ($periods as $period) {

            //     $appointments = Booking::where('barber_id', $barber->uniqueid)
            //         ->whereMonth('date','=',$period['month'])
            //         //->whereBetween('date', [$period['start'], $period['end']])
            //         ->where('status', 'complete')
            //         ->get();

            //     $walkins = Walkin::where('barber_id', $barber->uniqueid)
            //         ->whereMonth('date','=',$period['month'])
            //         //->whereBetween('date', [$period['start'], $period['end']])
            //         ->get();

            //     $totalServices = $appointments->count() + $walkins->count();
            //     $totalEarned = 0;

            //     foreach ($appointments as $appointment) {
            //         if ($appointment->service) {
            //             $totalEarned += $appointment->service->pricing;
            //         }
            //     }

            //     foreach ($walkins as $walkin) {
            //         if ($walkin->service) {
            //             $totalEarned += $walkin->service->pricing;
            //         }
            //     }

            //     $appointmentsDiscount = $appointments->filter(function ($appointment) {
            //         return $appointment->discount_type === 'barber';
            //     })->sum('discount');

            //     $walkinsDiscount = $walkins->filter(function ($walkin) {
            //         return $walkin->discount_type === 'barber';
            //     })->sum('discount');

            //     $totalDiscount = $appointmentsDiscount + $walkinsDiscount;
            //     $totalCommission = ($totalEarned) * ($period['rate'] / 100);

            //     if($totalServices != 0 && $totalEarned != 0){
            //         $barberunique[] = [
            //             'barber' => $barber,
            //             'totalServices' => $totalServices,
            //             'totalEarned' => $totalEarned,
            //             'totalDiscount' => $totalDiscount,
            //             'commissionRate' => $period['rate'],
            //             'totalCommission' => $totalCommission,
            //             'status' => $period['status'],
            //             'periodMonth' => $period['month'],
            //             // 'periodEnd' => $period['end'],
            //         ];
            //     }


            // }
            array_push($barberData,$barberunique);

    //logger($barberData);

    return view('reports.salary_report', [
        'barberData' => $barberData,
        'start_date' => $start_date,
    ]);

}



    // Performance Report
    public function performanceReport(Request $request) {
        $current_date = Carbon::now()->format('Y-m-d');
        $previous_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date || !$end_date) {
            $start_date = $previous_month;
            $end_date = $current_date;
        }
        $barbers = Barber::all();
        $services = Service::all();
        $performanceData = [];
        foreach ($barbers as $barber) {
            $barberPerformance = [
                'barber_name' => $barber->barber_name,
                'services' => [],
                'total_serviced' => 0,
            ];
            foreach ($services as $service) {
                $bookingsCount = Booking::where('barber_id', $barber->uniqueid)
                                        ->where('service_id', $service->uniqueid)
                                        ->whereBetween('date', [$start_date, $end_date])
                                        ->where('status', 'complete')
                                        ->count();
                $walkinsCount = Walkin::where('barber_id', $barber->uniqueid)
                                      ->where('service_id', $service->uniqueid)
                                      ->whereBetween('date', [$start_date, $end_date])
                                      ->count();
                $totalServiced = $bookingsCount + $walkinsCount;
                $barberPerformance['services'][$service->id] = $totalServiced;
                $barberPerformance['total_serviced'] += $totalServiced;
            }
            $performanceData[] = $barberPerformance;
        }
        return view('reports.barber_performance_report', [
            'performanceData' => $performanceData,
            'services' => $services,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }


    // Service Report
    public function servicesReport(Request $request){
        $current_date = Carbon::now()->format('Y-m-d');
        $previous_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date || !$end_date) {
            $start_date = $previous_month;
            $end_date = $current_date;
        }

        $services = Service::all();
        $serviceData = [];

        foreach ($services as $service) {
            $bookings = Booking::where('service_id', $service->uniqueid)
                               ->whereBetween('created_at', [$start_date, $end_date])
                               ->get();

            $walkins = Walkin::where('service_id', $service->uniqueid)
                             ->whereBetween('created_at', [$start_date, $end_date])
                             ->get();

            $totalBookings = $bookings->count();
            $totalWalkins = $walkins->count();
            $totalCount = $bookings->count() + $walkins->count();
            $totalEarned = 0;
            foreach ($bookings as $booking) {
                if ($booking->service) {
                    $totalEarned += $booking->amount;
                }
            }

            foreach ($walkins as $walkin) {
                if ($walkin->service) {
                    $totalEarned += $walkin->amount;
                }
            }

            $serviceData[] = [
                'service' => $service,
                'totalCount' => $totalCount,
                'totalBookings' => $totalBookings,
                'totalWalkins' => $totalWalkins,
                'totalEarning' => $totalEarned,
            ];
        }

        return view('reports.services_report', [
            'serviceData' => $serviceData,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    // Product Report
    public function productReport(Request $request){
        $current_date = Carbon::now()->format('Y-m-d');
        $previous_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date || !$end_date) {
            $start_date = $previous_month;
            $end_date = $current_date;
        }
        $products = Product::all();
        $productReportData = [];
        foreach ($products as $product) {
            $sales = ProductSold::where('product_id', $product->uniqueid)
                        ->whereBetween('created_at', [$start_date, $end_date])
                         ->get();
            $totalCount = $sales->sum('qty');
            $totalAmount = $sales->sum('total_amount');
            $total=$product->quantity;
            $productReportData[] = [
                'product_name' => $product->product_name,
                'total'=>$total,
                'total_count' => $totalCount,
                'total_amount' => $totalAmount,
            ];
        }
        return view('reports.products_report',[
            'productReportData' => $productReportData,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    // Expense Report
    public function expenseIncomeReport(Request $request){
        $current_date = Carbon::now()->format('Y-m-d');
        $previous_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date || !$end_date) {
            $start_date = $previous_month;
            $end_date = $current_date;
        }

        $appointments = Booking::whereBetween('created_at', [$start_date, $end_date])
                                ->where('status', 'complete')
                                ->get();

        $walkins = Walkin::whereBetween('created_at', [$start_date, $end_date])
                         ->get();

        $productsolds = ProductSold::whereBetween('created_at', [$start_date, $end_date])
                         ->get();

        $expenses = Expense::whereBetween('created_at', [$start_date, $end_date])
                           ->get();

        $reportData = [];
        $totalIncome = 0;
        $totalExpense = 0;

        // Process appointments
        foreach ($appointments as $appointment) {
            $row = [
                'date' => $appointment->created_at,
                'description' => 'Booking',
                'income' => $appointment->amount,
                'expense' => 0,
            ];
            if ($appointment->discount_type === 'shop') {
                $row['income'] -= $appointment->discount;
            }
            $totalIncome += $row['income'];
            $reportData[] = $row;
        }

        // Process walk-ins
        foreach ($walkins as $walkin) {
            $row = [
                'date' => $walkin->created_at,
                'description' => 'Walk-in',
                'income' => $walkin->amount,
                'expense' => 0,
            ];
            if ($walkin->discount_type === 'shop') {
                $row['income'] -= $walkin->discount;
            }
            $totalIncome += $row['income'];
            $reportData[] = $row;
        }
        // Process product solds
        foreach ($productsolds as $product) {
            $row = [
                'date' => $product->created_at,
                'description' => 'Soldout Products',
                'income' => $product->total_amount,
                'expense' => 0,
            ];

            $totalIncome += $row['income'];
            $reportData[] = $row;
        }

        // Process expenses
        foreach ($expenses as $expense) {
            $row = [
                'date' => $expense->date,
                'description' => 'Expense',
                'income' => 0,
                'expense' => $expense->amount,
            ];

            $totalExpense += $row['expense'];
            $reportData[] = $row;
        }

        $totalProfit = $totalIncome - $totalExpense;

        // Pass the data to the view
        return view('reports.expenses_report', [
            'reportData' => $reportData,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalProfit' => $totalProfit,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

}
