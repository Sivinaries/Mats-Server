<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use App\Models\Histoy;
use App\Models\Expense;
use App\Models\Discount;
use App\Models\Settlement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class Pagescontroller extends Controller
{
    public function dashboard(Request $request)
    {
        // CARDS
        $total_order = Order::count();
        $total_product = Menu::count();
        $total_users = User::where('level', 'user')->count();
        $top_seller = Histoy::selectRaw("SUBSTRING_INDEX(`order`, ' - ', 1) AS product_name")
            ->groupBy('order')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->pluck('product_name')
            ->first();

        // CHARTS ORDER
        $orders = Histoy::selectRaw("COUNT(*) as count, DATE_FORMAT(created_at, '%M') as month_name, MONTH(created_at) as month_number")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month_number', 'month_name')
            ->orderBy('month_number')
            ->pluck('count', 'month_name');

        $labels1 = $orders->keys();
        $data1 = $orders->values();

        // CHARTS REVENUE
        $revenue = Histoy::selectRaw("SUM(total_amount) as revenue, DATE_FORMAT(created_at, '%M') as month_name, MONTH(created_at) as month_number")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month_number', 'month_name')
            ->orderBy('month_number')
            ->pluck('revenue', 'month_name');

        $labels2 = $revenue->keys();
        $data2 = $revenue->values();

        // CHARTS SETTLEMENT
        $settlements = Settlement::selectRaw('DATE(start_time) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->get();

        $labels3 = $settlements->pluck('date')->toArray(); // Convert to array
        $data3 = $settlements->pluck('total')->toArray(); // Ensure this matches the sum total key

        // CHARTS EXPENSE
        $expense = Expense::selectRaw("SUM(nominal) as expense, DATE_FORMAT(created_at, '%M') as month_name, MONTH(created_at) as month_number")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month_number', 'month_name')
            ->orderBy('month_number')
            ->pluck('expense', 'month_name');

        $labels4 = $expense->keys();
        $data4 = $expense->values();

        // Variables related to filtering (optional: if you're using year/month filters)
        $selectedYear = $request->input('selectedYear', date('Y'));
        $selectedDate = $request->input('selectedDate', date('m'));

        // You can define $dataSets if needed (e.g., for multi-data charts)
        $dataSets = [
            'orders' => $data1,
            'revenue' => $data2,
            'settlements' => $data3,
            'expenses' => $data4,
        ];

        return view('dashboard', [
            'total_order' => $total_order,
            'total_product' => $total_product,
            'total_users' => $total_users,
            'top_seller' => $top_seller,
            'labels1' => $labels1,
            'data1' => $data1,
            'labels2' => $labels2,
            'data2' => $data2,
            'labels3' => $labels3,
            'data3' => $data3,
            'labels4' => $labels4,
            'data4' => $data4,
            'dataSets' => $dataSets,
            'selectedYear' => $selectedYear,
            'selectedDate' => $selectedDate
        ]);
    }
}
