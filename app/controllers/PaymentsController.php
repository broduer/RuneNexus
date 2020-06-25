<?php
use Illuminate\Pagination\Paginator;

class PaymentsController extends Controller {

    public function index($search = null, $page = 1) {
        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });

        if ($search != null) {
            $payments = Payments::where("username", $search);
        }
        $payments = Payments::paginate(per_page);
    }
}