<?php

namespace App\Http\Controllers;

use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BalanceSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inputDate = Input::get('c') ? Input::get('c') : Carbon::now()->format('Y-m');
        $current = Carbon::createFromFormat('Y-m', $inputDate);

        $year = $current->year;

        $calendar = [
            'current' => $current,
            'next' => (clone $current)->addMonth(),
            'prev' => (clone $current)->addMonth('-1')
        ];
        $projects = Project::visible()->with([
            'contract',
            'outputValues' => function($query) use ($year) {
                $query->where('year', '<=', $year);
            },
            'receipts' => function($query) use ($year) {
                $query->where('year', '<=', $year);
            },
            //'receipts.total',
            'capitalPlans'=> function($query) use ($current) {
                $query->where('calendar', '<=', $current);
            },
            'sections' => function($query) use ($current) {
                $query->where('completion_date', '<=', (clone $current)->lastOfMonth());
            },
        ])->has('contract')->get();

        $balances = collect();

        foreach ($projects as $index=>$project) {

            // 按照公式计算结果
            $signed_money = $project->contract->signed_money;
            if (!($signed_money>0)) {
                continue;
            }
            $last_month_output_value = $project->outputValues->where('year', $calendar['prev']->year)->first()['m_'.$calendar['prev']->month];
            $last_month_receipt = $project->receipts->where('year', $calendar['prev']->year)->first()['m_'.$calendar['prev']->month];
            $total_output_value = 0.0;
            $total_pay_times = 0;

            foreach ($project->outputValues as $output) {
                $total_output_value += $output->totalBefore($calendar['current']);
                $total_pay_times += $output->timesBefore($calendar['current']);
            }

            $advance_payment_amount = $project->contract->advance_payment_amount;
            $progress_payment_pct = $project->contract->progress_payment_pct;
            $current_month_receivable = $last_month_output_value * $progress_payment_pct / 100;
            $total_receivable = $total_output_value * $progress_payment_pct / 100;

            // 根据预方式计算预付款扣除
            $advance_payment_times = $project->contract->getOriginal('advance_payment_times');
            if (is_null($advance_payment_times)) {
                $advance_payment_deducted = $advance_payment_amount * ($total_output_value / $signed_money);
            } else if ($advance_payment_times >= $total_pay_times || $advance_payment_times == 0) {
                $advance_payment_deducted = $advance_payment_amount;
            } else {
                $advance_payment_deducted = $total_pay_times / $advance_payment_times * $advance_payment_amount;
            }

            $total_receipt = 0.0;
            foreach ($project->receipts as $receipt) {
                $total_receipt += $receipt->totalBefore($calendar['current']);
            }
            $total_arrears = $available_capital = $total_receivable + $advance_payment_amount - $advance_payment_deducted - $total_receipt;
            $planned_expenditure = $project->capitalPlans->sum('payment_amount');

            // 按部位计算项目重新计算必要项
            $pay_mode = $project->contract->getOriginal('pay_mode');
            $current_month_receivable_warning = false;
            $advance_payment_deducted_warning = false;
            $total_receivable_warning = false;

            if ($pay_mode == 1) {
                if ($project->sections->isEmpty()) {
                    $current_month_receivable_warning = true;
                    $total_receivable_warning = true;
                } else {
                    $last_month_output_value = $project->sections
                        ->where('completion_date', '>=', (clone $calendar['prev'])->firstOfMonth())
                        ->where('completion_date', '<=', (clone $calendar['prev'])->lastOfMonth())
                        ->sum('receivable');
                    $total_sections_output_value = $project->sections->sum('receivable');
                    $total_pay_times = $advance_payment_deducted = $project->sections->count();

                    // 预付款扣除重计算
                    $customize = $project->sections->where('advance_deducted', '>=', '0')->isNotEmpty();
                    if ($customize && !is_null($advance_payment_times)) {
                        $advance_payment_deducted_warning = true;
                    }
                    if ($customize) {
                        $advance_payment_deducted = $project->sections->sum('advance_deducted');
                    } else {
                        if (is_null($advance_payment_times)) {
                            $advance_payment_deducted = $advance_payment_amount * ($total_sections_output_value / $signed_money);
                        } else if ($advance_payment_times >= $total_pay_times || $advance_payment_times == 0) {
                            $advance_payment_deducted = $advance_payment_amount;
                        } else {
                            $advance_payment_deducted = $total_pay_times / $advance_payment_times * $advance_payment_amount;
                        }
                    }

                    $current_month_receivable = $last_month_output_value * $progress_payment_pct / 100;;
                    $total_receivable = $project->sections->sum('receivable') * $progress_payment_pct / 100;
                    $available_capital = $total_receivable + $advance_payment_amount - $advance_payment_deducted - $total_receipt;
                }
            }
            $available_capital_warning = $current_month_receivable_warning && $advance_payment_deducted_warning;

            $balances->push(collect([
                'id' => $project->id,
                'name' => $project->name,
                'signed_money' => $signed_money,
                'pay_mode' => $project->contract->pay_mode,
                'advance_payment_amount' => $advance_payment_amount,
                'advance_payment_mode' => $project->contract->advance_payment_mode,
                'manager' => $project->manager,
                'build_area' => $project->build_area,
                // 总产值
                'total_output_value' => $total_output_value,
                // 上个月产值
                'last_month_output_value' => $last_month_output_value,
                // 按进度付款比例
                'progress_payment_pct' => $progress_payment_pct,
                // 当月应收
                'current_month_receivable' => $current_month_receivable,
                // 累计应收
                'total_receivable' => $total_receivable,
                // 已扣除预付款
                'advance_payment_deducted' => $advance_payment_deducted,
                // 累计已收
                'total_receipt' => $total_receipt,
                // 可收
                'available_capital' => $available_capital,
                // 欠款总额
                'total_arrears' => $total_arrears,
                // 计划支出
                'planned_expenditure' => $planned_expenditure,
                // 上月实收
                'last_month_receipt' => $last_month_receipt,
                'warnings' => [
                    'current_month_receivable' => $current_month_receivable_warning,
                    'total_receivable' => $total_receivable_warning,
                    'advance_payment_deducted' => $advance_payment_deducted_warning,
                    'available_capital' => $available_capital_warning,
                ]
            ]));
        }

        return view('charts.balance', compact('calendar', 'balances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
