

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Robots" content="index,follow"/>
    <style>
        .header{
            font-family: arial, sans-serif;
            font-size: 20px;
        }
        table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }

        @page {
            margin-left: 25px;
            margin-right: 25px;
            margin-top: 25px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <div>
        <div class="issue_info">
            <center class="header">
            <strong>
                {{ ucwords($type) }} {{ __('report for') }} {{ $user->name }}
            </strong>
            </center>
            <hr>
            <table>
                <thead>
                    <tr>
                        <th scope="col" width="2%">{{ __('Sl.') }}</th>
                        <th scope="col" width="8%">{{ __('Trx') }}</th>
                        <th scope="col" width="5%">{{ __('Amount') }}</th>
                        <th scope="col" width="5%">{{ __('Balance') }}</th>
                        <th scope="col" width="5%">{{ __('Fee') }}</th>
                        <th scope="col" width="5%">{{ __('Status') }}</th>
                        <th scope="col" width="15%">{{ __('Type') }}</th>
                        <th scope="col">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $row->trxid }}</td>
                        <td>{{ $row->amount }}</td>
                        <td>{{ $row->balance }}</td>
                        <td>{{ $row->fee }}</td>
                        @if($row->status == 1)
                        <td class="text-success">{{ __('Active') }}</td>
                        @endif
                        @if($row->status == 0)
                        <td class="text-danger">{{ __('Inactive') }}</td>
                        @endif
                        <td>{{ $row->type }}</td>
                        <td>{{ date('d-m-Y h:i', strtotime($row->created_at)) }}</td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            <br/><br/>
        </div>
    </div>
</div>
</body>
</html>
