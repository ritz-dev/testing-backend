<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Cancellation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 5px;
            background-color: #f9f9f9;
        }

        .content {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 10px;
        }

        h2 {
            color: #333333;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #f4f4f4;
        }

        table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #E2E5DE;
            color: #000;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
        }

        .signature p {
            margin: 0;
            font-size: 14px;
            color: #777777;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777777;
            margin-top: 30px;
        }
        .row {
            display: flex;
        }

        .col-4 {
            flex: 0 0 33.33%;
            max-width: 33.33%;
        }

        .col-8 {
            flex: 0 0 66.66%;
            max-width: 66.66%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="content">
        <div class="row">
            <div class="col-4">
                {{-- <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('path/to/image.jpeg'))) }}" alt="img" width="150px"> --}}
                {{-- <img src="http://127.0.0.1:8000/path/to/image.jpeg" alt="img" width="150px"> --}}
            </div>
            <div class="col-8">
                <h4>NOT BARBER SHOP</h4>
                <p>{{ DB::table('shop_addresses')->first()->address }}<br>
                    {{ DB::table('shop_addresses')->first()->contact_number }}
                </p>
            </div>
        </div><hr>
        <h2>Appointment Cancellation</h2>
        <p>Dear <b>{{ $data['booking_infos']->customer_name }}</b>,</p>

        <p>We regret to inform you that your appointment with {{ $data['booking_infos']->barber_name }} on {{ date('d-m-Y', strtotime($data['booking_infos']->date ))}} at {{ Carbon\Carbon::parse($data['booking_infos']->time_period)->format('g:i A') }} has been canceled.</p>

        <p style="font-weight:bold">Cancellation Details:</p>
        <table>
            <tr>
                <td><strong>Service</strong></td>
                <td>
                    @foreach ($data['services'] as $service)
                        {{ $service->service_name }} ( {{ $service->duration }} Minutes ) ,
                    @endforeach
                </td>
            </tr>
            <tr>
                <td><strong>Barber/Service Provider</strong></td>
                <td>{{ $data['booking_infos']->barber_name }}</td>
            </tr>
            <tr>
                <td><strong>Date</strong></td>
                <td>{{ date('d-m-Y', strtotime($data['booking_infos']->date)) }}</td>
            </tr>
            <tr>
                <td><strong>Time</strong></td>
                <td>{{ Carbon\Carbon::parse($data['booking_infos']->time_period)->format('g:i A') }}</td>
            </tr>
            <tr>
                <td><strong>Total Amount</strong></td>
                <td>{{ number_format($data['total_amount']) }} MMK</td>
            </tr>
        </table>

        <p>We apologize for any inconvenience this may cause. If you have any questions or need further assistance, please don't hesitate to contact us at 09-123 456 789.</p>

        <p>Thank you for your understanding.</p>

        <div class="signature">
            <p>Best regards,</p>
            <p>NOT BARBER SHOP</p>
        </div>
    </div>

    <p class="footer">This email is for appointment cancellation purposes only. Please do not reply to this email.</p>

</div>

</body>
</html>
