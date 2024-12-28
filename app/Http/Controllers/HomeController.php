<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        if (view()->exists('dashboard/' . $request->path())) {
            return view('dashboard/' . $request->path());
        }
        return abort(404);
    }

    public function root()
    {
        // total revunue calculations
        $totalDonePayments = Payment::where('status', 'done')->sum('amount');
        $currentMonthDonePayments = Payment::where('status', 'done')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        $previousMonthDonePayments = Payment::where('status', 'done')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('amount');
        $growthPercentage = $previousMonthDonePayments > 0 && $currentMonthDonePayments > 0 && $previousMonthDonePayments < $currentMonthDonePayments
            ? (($currentMonthDonePayments - $previousMonthDonePayments) / $previousMonthDonePayments) * 100
            : null;
        $totalRevenue = ['totalRevenue' => $totalDonePayments, 'growthPercentage' => $growthPercentage];

        //orders calculations
        $totalDoneOrdersCount = Order::whereHas('payment', function ($query) {
            $query->where('status', 'done');
        })->count();
        $currentMonthDoneOrdersCount = Order::whereHas('payment', function ($query) {
            $query->where('status', 'done');
        })->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $previousMonthDoneOrdersCount = Order::whereHas('payment', function ($query) {
            $query->where('status', 'done');
        })->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        $ordersGrowthPercentage = $previousMonthDoneOrdersCount > 0 && $currentMonthDoneOrdersCount > 0 && $previousMonthDoneOrdersCount < $currentMonthDoneOrdersCount
            ? (($currentMonthDoneOrdersCount - $previousMonthDoneOrdersCount) / $previousMonthDoneOrdersCount) * 100
            : 0;
        $totalOrders = ['totalOrders' => $totalDoneOrdersCount, 'ordersGrowthPercentage' => $ordersGrowthPercentage];

        //students calculations
        $totalStudents = User::role('student', 'api')->count();
        $currentMonthStudents = User::role('student', 'api')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $previousMonthStudents = User::role('student', 'api')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        $studentsGrowthPercentage = $previousMonthStudents > 0 && $currentMonthStudents > 0 && $previousMonthStudents < $currentMonthStudents
            ? (($currentMonthStudents - $previousMonthStudents) / $previousMonthStudents) * 100
            : 0;
        $totalStudents = ['totalStudents' => $totalStudents, 'growthPercentage' => $studentsGrowthPercentage];

        //teachers calculations
        $totalTeachers = User::role('student', 'api')->count();
        $currentMonthTeachers = User::role('teacher', 'api')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $previousMonthTeachers = User::role('teacher', 'api')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        $teachersGrowthPercentage = $previousMonthTeachers > 0 && $currentMonthTeachers > 0 && $previousMonthTeachers < $currentMonthTeachers
            ? abs((($currentMonthTeachers - $previousMonthTeachers) / $previousMonthTeachers) * 100)
            : 0;
        $totalTeachers = ['totalTeachers' => $totalTeachers, 'growthPercentage' => $teachersGrowthPercentage];

        //top teachers
        $topTeachers = User::whereHas('teacherCourses')->withCount('teacherCourses')->orderBy('teacher_courses_count', 'desc')->limit(5)->get();

        //Top selling Courses
        $topCoursesModel = Course::whereHas('orders', function ($query) {
            $query->whereHas('payment', function ($q) {
                $q->where('status', 'done');
            });
        })->withCount('orders')->orderBy('orders_count', 'desc')->limit(7)->get();
        $totalCoursesOrders = $topCoursesModel->flatMap(function ($course) {
            return $course->orders;
        })->count();
        $topCourses = ['topCourses' => $topCoursesModel, 'totalCoursesOrders' => $totalCoursesOrders];

        //latest transactions
        $latestTransactions = Order::latest()->limit(8)->get();
        return view('index', compact('totalRevenue', 'totalOrders', 'totalStudents', 'totalTeachers', 'topTeachers', 'topCourses', 'latestTransactions'));
    }

    public function getPackageCourseCounts()
    {
        $startDate = now()->subYear();  // One year ago
        $endDate = now()->addMonth();  // Today

        // Get months from startDate to endDate
        $months = [];
        for ($date = $startDate; $date->lte($endDate); $date->addMonth()) {
            $months[] = $date->format('Y-m');
        }

        // Get the package and course counts for each month
        $packageCounts = [];
        $courseCounts = [];

        foreach ($months as $month) {
            $firstDayOfMonth = Carbon::parse($month)->startOfMonth();  // First day of the month
            $lastDayOfMonth = Carbon::parse($month)->endOfMonth();  // Last day of the month
            $packages = Package::whereHas('orders', function ($query) use ($firstDayOfMonth, $lastDayOfMonth) {
                $query->whereHas('payment', function ($q) {
                    $q->where('status', 'done');
                })->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth]);
            })->get();
            $totalPackageOrders = $packages->flatMap(function ($package) {
                return $package->orders;
            })->count();
            $packageCounts[] = $totalPackageOrders;

            $courses = Course::whereHas('orders', function ($query) use ($firstDayOfMonth, $lastDayOfMonth) {
                $query->whereHas('payment', function ($q) {
                    $q->where('status', 'done');
                })->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth]);
            })->get();
            $totalCoursesOrders = $courses->flatMap(function ($course) {
                return $course->orders;
            })->count();
            $courseCounts[] = $totalCoursesOrders;
        }

        return response()->json([
            'months' => $months,
            'package_counts' => $packageCounts,
            'course_counts' => $courseCounts,
        ]);
    }


    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function FormSubmit(Request $request)
    {
        return view('form-repeater');
    }
}
