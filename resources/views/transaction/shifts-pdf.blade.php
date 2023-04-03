<!DOCTYPE html>
<html>
<head>
	<title>Shifts Report</title>
	<style>
		table {
			border-collapse: collapse;
			width: 100%;
		}
		th, td {
			border: 1px solid black;
			padding: 8px;
			text-align: left;
		}
		th {
			background-color: #ddd;
		}
	</style>
</head>
<body>

<h4 class="mb-sm-0 font-size-18">Cashier Report</h4>
    <table class="table align-middle table-nowrap table-check" id="transact-form"> 
        <thead class="table-light">
            <tr>
                <th class="align-middle">Date</th>
                <th class="align-middle">Attendant </th>
                <th class="align-middle">Total Drop</th>
                <th class="align-middle">Expected</th>
                <th class="align-middle">Short/Gain</th>
                <th class="align-middle">Comment</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->date }}</td>
                <td>{{ $transaction->attendant_name}}</td>
                <td>{{number_format(floatval($transaction->total) + floatval($transaction->coins) + floatval($transaction->recovery)) }}</td>
                <td>{{ number_format((float)$transaction->expected) }}</td>
                <td style="background-color: {{ $transaction->difference < 0 ? 'red' : 'green' }}; color: white;">    
                    @if ($transaction->difference < 0)
                        {{ number_format(($transaction->difference)) }}
                    @elseif ($transaction->difference == 0)
                        0
                    @else
                        {{ number_format($transaction->difference) }}
                    @endif
                </td>

                <td>
                    @if (!is_null($transaction->comment))
                        @php
                            $words = preg_split('/\s+/', $transaction->comment);
                            if (is_array($words)) {
                                $words = array_slice($words, 0, 4);
                                $shortenedComment = implode(' ', $words);
                                if (!empty(trim($shortenedComment))) {
                                    echo $shortenedComment . '...';
                                } else {
                                    echo $transaction->comment;
                                }
                            } else {
                                echo $transaction->comment;
                            }
                        @endphp
                    @endif
                </td>
                                        
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
                         



