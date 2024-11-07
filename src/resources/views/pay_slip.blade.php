@extends('layouts.app')
@section('content')
<div id="printDeposit">

    <div id="invoice-POS">
        
        <div id="mid">
            <div class="info">
                <h3 class="text-center"
                    style="text-align: center; font-size: 16px;  margin-top: 20px">
                    Payslip</h3>
                <p style="text-align: center; font-size: 14px; margin-bottom: 0">Not Barber Shop</p>
                <p style="text-align: center; font-size: 14px; border-bottom: 1px solid grey; padding-bottom: 20px">{{ DB::table('shop_addresses')->first()->address }}</p>
                <div style="display: flex; justify-content: space-between; align-items-center; margin-bottom: 20px">
                    <div>
                        <p style="margin-bottom: 0;">Date: {{ date('d-m-Y') }}</p>
                        <p>Pay with: Cash</p>
                    </div>
                    <div>
                        <p style="margin-bottom: 0;">Employee name: </p>
                        <p>Time: <span id="time"></span></p>
                    </div>
                </div>
                <div style="line-height: 6px;">
                    <p style="font-size: 12px;">
                        ချေးငွေအမှတ်စဥ် : 
                    </p>
                    <p style="font-size: 12px;">
                        အမည် : 
                    </p>

                    <p style="font-size: 12px;">
                        ချေးငွေပမဏ :  ကျပ်
                    </p>

                    <p style="font-size: 12px;">
                        စရံ :  ကျပ်
                    </p>

                    <p style="font-size: 12px;">
                        စုဆောင်းငွေ :  ကျပ်
                    </p>

                    <p style="font-size: 12px;">
                        စုစုပေါင်းစရံ :  ကျပ်
                    </p>
                </div>
            </div>
        </div>
        <!--End Invoice Mid-->


        <div id="bot" style="border-top: 1px solid grey">

            <div id="legalcopy" style="text-align: center">
                <p class="legal" style="font-size: 6px">
                    <strong>Thank you for using mkt!</strong><br><br>
                    Myat Kyun Thar Microfinance (MKT)<br>
                    www.mkt.com.mm<br>
                    0912345677
                </p>
            </div>
        </div>
        <!--End InvoiceBot-->
    </div>
    <!--End Invoice-->
</div>
@endsection