<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title> Loan Disbursement Email</title>
    <meta name="description" content="Loan Disbursement Email">
    <style type="text/css">
        a:hover {text-decoration: underline !important;}
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
    <!--100% body table-->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
        style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                    align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                    <!-- <tr>
                        <td style="text-align:center;">
                            <a href="https://mweguni.co.ke" title="logo" target="_blank">
                                <img width="60" src="https://www.loanapp.com/images/logo.png" title="logo" alt="logo">
                            </a>
                        </td>

                    <tr> -->
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans serif;">
                                        <!--customer name  from $details array-->
                                        Hello {{$disbursement['customer_name']}}, 
                                        </h1>
                                        <span
                                            style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                        
                                        <!--loan amount from $details array-->
                                        Your loan amount of Ksh. {{$disbursement['loan_amount']}} has been disbursed to your {{$disbursement['customer_phone']}}.
                                        The loan is to be repaid in {{$disbursement['loan_duration']}} days starting from {{$disbursement['loan_start_date']}} to {{$disbursement['loan_end_date']}}.
                                        The total amount payable is Ksh. {{$disbursement['loan_total_payable']}}.
                                        
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <p style="font-size:14px; line-height:18px; color:#a2a2a7; margin-top:0;">
                                &copy; 
                                <!-- current year -->
                                {{date('Y')}}
                                <strong>Mweguni Enterprises</strong>. All Rights Reserved.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
</body>

</html>
