<?php
class AdminController extends Controller {

    public function index() {
        $thisMonth = strtotime(date("Y-m-01 00:00:00"));
        
        $data = [
            'users' => [
                'total' => Users::count(),
                'month' => Users::where("join_date", ">=", $thisMonth)->count()
            ],
            'payments' => [
                'total' => Payments::sum('paid'),
                'month' => Payments::where("date_paid", ">=", $thisMonth)->sum('paid'),
            ],
            'servers' => [
                'total' => Servers::count(),
                'month' => Servers::where("date_created", ">=", $thisMonth)->count(),
            ]
        ];

        $dates    = $this->getChartDates(30);
        
        $payments = Payments::getChartData($dates);
        $members  = Users::getChartData($dates);
        $servers  = Servers::getChartData($dates);

        $this->set("chart_keys", array_keys($dates['chart']));
        $this->set("payment_data", $payments);
        $this->set("members_data", $members);
        $this->set("servers_data", $servers);
        $this->set("data", $data);
        return true;
    }

    public function getChartDates($dayLimit = 14, $format = "m.d") {
        $start = strtotime(date("Y-m-d 00:00:00")." -$dayLimit days");
        $end   = strtotime(date("Y-m-d 23:59:59"));

        $data = [
            'start'  => $start,
            'format' => $format,
            'chart'  => [],
        ];

        while($start < $end) {
            $start += 86400; // increment by 1 day until we reach today
            $date = date($format, $start);
            $data['chart'][$date] = 0;
        }

        return $data;
    }

    public function beforeExecute() {
        return parent::beforeExecute();
    }

}