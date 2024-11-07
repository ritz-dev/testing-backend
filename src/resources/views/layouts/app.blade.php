<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Not Barber Shop Admin Dashboard</title>

    <link rel="shortcut icon" type="image/x-icon"
        href="https://dreamspos.dreamguystech.com/html/template/assets/img/favicon.png">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"
        integrity="sha512-rqQltXRuHxtPWhktpAZxLHUVJ3Eombn3hvk9PHjV/N5DMUYnzKPC1i3ub0mEXgFzsaZNeJcoE0YHq0j/GFsdGg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        @include('layouts.app_nav')

        <div class="page-wrapper page-wrapper-one">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>


    {{-- <script src="{{ asset('assets/js/index.global.min.js') }}"></script> --}}

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

    <script src="{{ asset('assets/js/feather.min.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"
        integrity="sha512-pdCVFUWsxl1A4g0uV6fyJ3nrnTGeWnZN2Tl/56j45UvZ1OMdm9CIbctuIHj+yBIRTUUyv6I9+OivXj4i0LPEYA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script> --}}

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>

    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/pdfGenerate.js') }}"></script>
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

    <script>
        let chartArrayValue;
        $.ajax({
            url: "{{ url('/api/get-chart') }}",
            method: "GET",
            success: function(res) {
                chartArrayValue = res;
                // console.log(chartArrayValue);

                // Simple Bar
                if ($('#s-bar').length > 0) {
                    var sBar = {
                        chart: {
                            height: 350,
                            type: 'bar',
                            toolbar: {
                                show: false,
                            }
                        },
                        // colors: ['#4361ee'],
                        plotOptions: {
                            bar: {
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        series: [{
                            data: chartArrayValue
                        }],
                        xaxis: {
                            categories: ['January', 'Feburary', 'March', 'April', 'May', 'June', 'July',
                                'August', 'September',
                                'October', 'November', 'December'
                            ],min:0,
                        },
                    }

                    var chart = new ApexCharts(
                        document.querySelector("#s-bar"),
                        sBar
                    );

                    chart.render();
                }
            }
        });

        
        $('#expenseReportFilter').change(function() {
            $("#expenseReport").submit();
        });

        $(".bookingStatus").on('change', function() {
            let form = $(this).closest('form');
            let status = form[0][0];
            let booking_unid = form[0][1];
         	if(status.value == "complete"){
                $('#booking'+booking_unid.value).attr("disabled", true);
            }

            $.ajax({
                url: "{{ url('api/booking-status') }}",
                method: 'POST',
                data: {
                    status: status.value,
                    booking_unid: booking_unid.value
                },
                success: function(res) {
                    $("#" + booking_unid.value).text(res);
                    if (res === "complete") {
                        $("#" + booking_unid.value).removeClass("bg-danger");
                        $("#" + booking_unid.value).removeClass("bg-primary");
                        $("#" + booking_unid.value).removeClass("bg-warning");
                        $("#" + booking_unid.value).addClass("bg-lightgreen");
                    } else if (res === "cancel") {
                        $("#" + booking_unid.value).removeClass("bg-danger");
                        $("#" + booking_unid.value).removeClass("bg-primary");
                        $("#" + booking_unid.value).removeClass("bg-lightgreen");
                        $("#" + booking_unid.value).addClass("bg-warning");
                    } else if (res === "confirm") {
                        $("#" + booking_unid.value).removeClass("bg-lightgreen");
                        $("#" + booking_unid.value).removeClass("bg-primary");
                        $("#" + booking_unid.value).removeClass("bg-warning");
                        $("#" + booking_unid.value).addClass("bg-danger");
                    } else if (res === 'active') {
                        $("#" + booking_unid.value).removeClass("bg-danger");
                        $("#" + booking_unid.value).removeClass("bg-lightgreen");
                        $("#" + booking_unid.value).removeClass("bg-warning");
                        $("#" + booking_unid.value).addClass("bg-primary");
                    }
                }
            })
        });


        $("#date").change(function() {
            // console.log($(this).val(), $("#barber").val());
            $.ajax({
                url: "{{ url('api/check') }}",
                method: 'GET',
                data: {
                    date: $(this).val(),
                    barber: $("#barber").val()
                },
                success: function(res) {
                    let times = res;
                    // console.log(res);
                    $("#timePeriod").empty();
                    times.forEach(function(time) {

                        $("#timePeriod").append(`
                            <option value="${time.uniqueid}">${time.time_period}</option>
                        `);
                    })
                },
                error: function(err) {
                    alert('Encounter error');
                }
            })
        });

        $("#barber").change(function() {
            // console.log($(this).val(), $("#date").val());
            $.ajax({
                url: "{{ url('api/check') }}",
                method: 'GET',
                data: {
                    barber: $(this).val(),
                    date: $("#date").val()
                },
                success: function(res) {
                    let times = res;
                    // console.log(res);
                    $("#timePeriod").empty();
                    times.forEach(function(time) {

                        $("#timePeriod").append(`
                            <option value="${time.uniqueid}">${time.time_period}</option>
                        `);
                    })
                },
                error: function(err) {
                    alert('Encounter error');
                }
            })
        })
        //products sold form
        $("#product_select").change(function() {
            var product_uniqueid = $(this).val();
            $.ajax({
                url: "{{ url('products/details/') }}",
                method: 'GET',
                data: {
                    product_uniqueid
                },
                success: function(res) {
                    // console.log(res);
                    $('#price').val(res.price + " MMK");
                    var newImageUrl = "{{ url('storage/product_photos/') }}" + '/' + res.photo;
                    $('#product_photo').attr('src', newImageUrl);

                },
                error: function(err) {
                    alert('Encounter error');
                }
            })
        })

        $('#qty').keyup(function() {
            var qty = $(this).val();
            var price = $('#price').val();
            var price = price.match(/\d+/)[0];

            var total_amount = qty * price;
            $('#total_amount').val(total_amount + " MMK");


        })

        //services_report_filter_search
        var searchBtn = document.getElementById('services_report_filter_search');
        searchBtn && searchBtn.addEventListener('click', function() {
            // console.log('Button clicked!');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            // console.log(from_date);
            // console.log(to_date);

            if ($("#reportType").val() === "product") {
                $.ajax({
                    url: "{{ url('products_report') }}",
                    method: "GET",
                    data: {
                        from_date,
                        to_date,
                    },
                    success: function(response) {
                        // console.log(response);
                        $('#filter').text("From " + response.from + " To " + response.to);
                        $("#products_report_tbody").empty();
                        var rowNumber = 1;
                        var grandTotal = 0;
                        var stocks = 0;
                        var soldStocks = 0;
                        $.each(response.data, function(index, value) {

                            $("#products_report_tbody").append('<tr><td>' + rowNumber +
                                '</td><td>' + value.product_name +
                                '</td><td>' +
                                value.date + '</td><td>' + value.qty + '</td><td>' +
                                value.total_amount.toLocaleString() + " MMK" + '</td><</tr>'
                            );

                            rowNumber++;
                            grandTotal += value.total_amount;
                            stocks = value.quantity;
                            soldStocks += value.qty;
                        });

                        $('#products_report_tbody').append(`<tr style="background: #dedede">
                                <td style="font-weight:800">Grand Total</td>
                                <td></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800">${grandTotal} MMK</td>
                            </tr>

                            <tr style="background: #dedede">
                                <td style="font-weight:800">Total Stocks</td>
                                <td></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800">${stocks}</td>
                            </tr>

                            <tr style="background: #dedede">
                                <td style="font-weight:800">Total Sold Stocks</td>
                                <td></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800">${soldStocks}</td>
                            </tr>`);
                    },
                });
            } else {
                $.ajax({
                    url: "{{ url('services_report') }}",
                    method: "GET",
                    data: {
                        from_date,
                        to_date,
                    },
                    success: function(response) {
                        // console.log(response);
                        $('#filter').text("From " + response.from + " To " + response.to);
                        $("#services_report_tbody").empty();
                        var rowNumber = 1;
                        $.each(response.data, function(index, value) {
                            var walkInCount = value.walk_in ? value.walk_in : 0;
                            var bookingCount = value.booking ? value.booking : 0;

                            $("#services_report_tbody").append('<tr><td>' + rowNumber +
                                '</td><td>' + index +
                                '</td><td>' +
                                value.count + '</td><td>' + bookingCount + '</td><td>' +
                                walkInCount + '</td><td>' + value.total_pricing
                                .toLocaleString() + " MMK" + '</td></tr>');

                            rowNumber++;

                        });

                    },
                });
            }

        });

        //services report month filter
        $('#month_filter').change(function() {
            var month = $(this).val();
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            // console.log(month);

            if ($("#reportType").val() === "product") {
                $.ajax({
                    url: "{{ url('products_report') }}",
                    method: "GET",
                    data: {
                        month
                    },
                    success: function(response) {
                        if (month === "this_year") {
                            $("#filter").text("This year");
                        } else {
                            $("#filter").text(monthNames[month - 1]);
                        }
                        // console.log(response);

                        $("#products_report_tbody").empty();
                        var rowNumber = 1;
                        var grandTotal = 0;
                        var stocks = 0;
                        var soldStocks = 0;
                        $.each(response.data, function(index, value) {
                            let formattedDate = new Date(value.date);
                            formattedDate =
                                `${String(formattedDate.getDate()).padStart(2, "0")}-${String(formattedDate.getMonth() + 1).padStart(2, "0")}-${formattedDate.getFullYear()}`;

                            $("#products_report_tbody").append('<tr><td>' + rowNumber +
                                '</td><td>' + value.product_name +
                                '</td><td>' +
                                formattedDate + '</td><td>' + value.qty + '</td><td>' +
                                value.total_amount.toLocaleString() + '</td><</tr>');

                            rowNumber++;
                            grandTotal += value.total_amount;
                            stocks = value.quantity;
                            soldStocks += value.qty;
                        });

                        $('#products_report_tbody').append(`<tr style="background: #dedede">
                                <td style="font-weight:800">Grand Total</td>
                                <td></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800">${grandTotal} MMK</td>
                            </tr>

                            <tr style="background: #dedede">
                                <td style="font-weight:800">Total Stocks</td>
                                <td></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800">${stocks}</td>
                            </tr>

                            <tr style="background: #dedede">
                                <td style="font-weight:800">Total Sold Stocks</td>
                                <td></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800"></td>
                                <td style="font-weight:800">${soldStocks}</td>
                            </tr>`);
                    }
                })

            } else if ($("#reportType").val() === "barber_performance") {
                $.ajax({
                    url: "{{ url('performance_report') }}",
                    method: "GET",
                    data: {
                        month
                    },
                    success: function(response) {
                        // console.log(response);
                        if (month === "this_year") {
                            $("#filter").text("This year");
                        } else {
                            $("#filter").text(monthNames[month - 1]);
                        }
                        $("#performance_report_tbody").empty();
                        var rowNumber = 1;
                        var servicesName= response.services;
                        // console.log(servicesName);
                        $.each(response.data, function(index, value) {
                            var row = '<tr><td>' + rowNumber + '</td><td>' + index + '</td>';

                            // Loop through the service names
                            for (var i = 0; i < servicesName.length; i++) {
                                var serviceName = servicesName[i].service_name;
                                var serviceValue = value[serviceName] || 0;
                                row += '<td>' + serviceValue + '</td>';
                            }

                            row += '<td>' + value['total_serviced'] + '</td></tr>';
                            $("#performance_report_tbody").append(row);
                            rowNumber++;
                        });
                    },
                });

            } else {
                $.ajax({
                    url: "{{ url('services_report') }}",
                    method: "GET",
                    data: {
                        month
                    },
                    success: function(response) {
                        // console.log(response);
                        $('#filter').text("From " + response.from + " To " + response.to);
                        $("#services_report_tbody").empty();
                        var rowNumber = 1;
                        var grandTotal = 0;
                        var total_count = 0;
                        var total_booking = 0;
                        var total_walk_in = 0;
                        $.each(response.data, function(index, value) {
                            var walkInCount = value.walk_in ? value.walk_in : 0;
                            var bookingCount = value.booking ? value.booking : 0;

                            $("#services_report_tbody").append('<tr><td>' + rowNumber +
                                '</td><td>' + index +
                                '</td><td>' +
                                value.count + '</td><td>' + bookingCount + '</td><td>' +
                                walkInCount + '</td><td>' + value.total_pricing
                                .toLocaleString() +
                                ' MMK</td></tr>');

                            rowNumber++;
                            total_count += value.count;
                            total_booking += bookingCount;
                            total_walk_in += walkInCount;
                            grandTotal += value.total_pricing;


                        });

                        $("#services_report_tbody").append(
                            '<tr style="background: #dedede"><td style="font-weight:800">Grand Total</td><td></td><td style="font-weight:800">' +
                            total_count + '</td><td style="font-weight:800">' +
                            total_booking + '</td><td style="font-weight:800">' + total_walk_in +
                            '</td><td style="font-weight:800">' + grandTotal +
                            ' MMK</td>');


                    },
                });
            }


        })

        //salary report filter
        $('#filter_by').change(function() {
            var month = $(this).val();
            // console.log(month);
            $.ajax({
                url: "{{ url('salary_report') }}",
                method: "GET",
                data: {
                    month
                },
                success: function(response) {
                    // console.log(response);
                    $('#filter').text(response.filterMonth);
                    $("#salary_report_tbody").empty();
                    var rowNumber = 1;
                    $.each(response.data, function(index, value) {

                        $("#salary_report_tbody").append('<tr><td>' + rowNumber +
                            '</td><td class="productimgname"><img src="{{ url('storage/barber_photos/') }}/' +
                            value.barber_photo +
                            '" alt="img" width="50px" class="rounded" style="height:50px;object-fit:cover"><span>' +
                            value.barber_name + '</span></td><td>' +
                            value.contact_number + '</td><td>' + value.serviced_count +
                            '</td><td>' + value.total_earned.toLocaleString() + " MMK" + '</td><td>' + value
                            .base_salary.toLocaleString() + " MMK" + '</td><td>' +
                            value.commission_rate + " %" + '</td><td>' + value
                            .barber_commission_earned.toLocaleString() + " MMK" +
                            '</td><td>' + value.total_salary.toLocaleString() + " MMK" + '</td><td>' + value
                            .join_date +
                            '</td></tr>');


                        rowNumber++;
                    });
                },
            });

        })

        $("#serviceReportExcelBtn").click(function() {
            $(".serviceReport").table2excel({
                exclude: ".excludeThisClass",
                name: "Worksheet Name",
                filename: "Service Report.xls",
                preserveColors: false
            });
        });

        $("#salaryReportExcelBtn").click(function() {
            $(".salaryReport").table2excel({
                exclude: ".excludeThisClass",
                name: "Worksheet Name",
                filename: "Salary Report.xls",
                preserveColors: false
            });
        });

        $("#productReportExcelBtn").click(function() {
            $("#productReport").table2excel({
                exclude: ".excludeThisClass",
                name: "Worksheet Name",
                filename: "Product Report.xls",
                preserveColors: false
            });
        });

$(".commission-rate").on('change', function () {
            var commissionStatus = $(this).val();

            var $closestTr = $(this).closest('tr');
            var barberId = $closestTr.find('input[name="barber_id"]').val();
            var commissionRate = $closestTr.find('input[name="commission_rate"]').val();
            var date = $closestTr.find('input[name="date"]').val();
            var totalEarn = $closestTr.find('input[name="total_rate"]').val();
            var authName = $closestTr.find('input[name="auth_name"]').val();

            if(commissionStatus == 'cancel'){
                $.ajax({
                    url: "{{ url('api/barber_commission_delete') }}",
                    method: "DELETE",

                    data: {
                        barber_id: barberId,
                        commission_rate: commissionRate,
                        date: date,
                    },

                    success: function(res){
                        window.location.reload();
                    }
                });
            }else{
                $.ajax({
                url: "{{ url('api/barber_commission') }}",
                method: "POST",

                data: {
                    barber_id: barberId,
                    commission_rate: commissionRate,
                    date: date,
                },

                success: function(res){

                    $("#" + barberId).text(totalEarn * (res.data / 100));
                    if(authName != "Admin"){
                        $closestTr.find('input[name="commission_rate"]').prop('disabled', true);
                        $closestTr.find('select[name="booking_status"]').prop('disabled', true);
                    }

                }
            })
            }

        })

    </script>

    @stack('scripts')

</body>

</html>
