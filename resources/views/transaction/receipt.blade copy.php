<div class="card">
    <div class="card-body py-20">
        <div class="mw-lg-950px mx-auto w-100">
            <!-- begin::Header-->
            <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">Loan Transaction Receipt</h4>

                <div class="text-sm-end">
                    <!--mweguni logo -->
                    <!-- <a href="#" class="d-block mw-150px ms-sm-auto">
                        <img alt="Logo" src="/metronic8/demo6/assets/media/svg/brand-logos/lloyds-of-london-logo.svg" class="w-100">
                    </a> -->
                    <!--end::Logo-->

                    <!--begin::Text-->
                    <div class="text-sm-end fw-semibold fs-4 text-muted mt-7">
                        <!-- <div>Cecilia Chapman, 711-2880 Nulla St, Mankato</div> -->
                        <!--set as rumuruti but change to branch-->
                        <div>Rumuruti, Nyahururu</div>
                        <a href="tel:0713723353">0713723353</a>
                    </div>
                    <!--end::Text-->
                </div>
            </div>
            <!--end::Header-->

            <!--begin::Body-->
            <div class="pb-12">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column gap-7 gap-md-10">
                    <!--begin::Message-->
                    <div class="fw-bold fs-2">
                        Dear {{$customer->first_name}} {{customer->last_name}},<br>
                        <!-- <span class="fs-6">(miller@mapple.com)</span>,<br> -->
                        <span class="text-muted fs-5">Here are your loan transaction details. For the Loan applied on {{$loan->created_at}}</span>
                    </div>

                    <div class="separator"></div>
                    
                    <div class="d-flex justify-content-between flex-column">
                        <!--begin::Table-->
                        <div class="table-responsive border-bottom mb-9">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                <thead>
                                    <tr class="border-bottom fs-6 fw-bold text-muted">
                                        <th class="min-w-175px pb-2">Transaction Code</th>
                                        <th class="min-w-70px text-end pb-2">Date</th>
                                        <th class="min-w-80px text-end pb-2">Amount</th>
                                        <!-- <th class="min-w-100px text-end pb-2">Total</th> -->
                                    </tr>
                                </thead>

                                <tbody class="fw-semibold text-gray-600">
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-5">
                                                    <div class="fw-bold">{{$transaction->transaction_code}}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            @php
                                            $date_string = $transaction->transaction_date;
                                            if (strlen($date_string) == 19) {
                                                $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date_string);
                                            } elseif (strlen($date_string) == 16) {
                                                $datetime = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $date_string);
                                            } elseif (strlen($date_string) == 10) {
                                                $datetime = \Carbon\Carbon::createFromFormat('Y-m-d', $date_string);
                                            } else {
                                                $datetime = \Carbon\Carbon::createFromFormat('Y-m-d', $date_string);
                                            }
                                            @endphp 
                                            {{$datetime->format('Y-m-d H:i')}}                                    
                                        </td>
                                        <td class="text-end">
                                            Ksh {{number_format($transaction->transaction_amount, 2)}}
                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="3" class="text-end">
                                            Total
                                        </td>
                                        <td class="text-end">
                                            $total_transaction_amount = $transactions->sum('transaction_amount');
                                            Ksh {{number_format($total_transaction_amount, 2)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">
                                            Total Payable
                                        </td>
                                        <td class="text-end">
                                            {{number_format($loan->total_payable, 2)}}
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td colspan="3" class="text-end">
                                            Shipping Rate
                                        </td>
                                        <td class="text-end">
                                            $5.00
                                        </td>
                                    </tr> -->
                                    @if ($loan->remaining_balance > 0) 
                                    <tr>
                                        <td colspan="3" class="fs-3 text-dark fw-bold text-end">
                                            Balance
                                        </td>
                                        <td class="text-dark fs-3 fw-bolder text-end">
                                            {{number_format($loan->remaining_balance, 2)}}
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>