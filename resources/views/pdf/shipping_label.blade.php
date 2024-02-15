<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shipping Label</title>
</head>
<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('laraveldaily.png') }}" alt="{{ config('app.name') }}" width="200" />
            </td>
            <td class="w-half">
                <h2>ORDER ID: {{ $order->id }}</h2>
            </td>
        </tr>
    </table>
 
    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div><h4>To:</h4></div>
                    <div>{{ $order->user->name }} {{ $order->user->lastname}}</div>
                    <div>{{ $shipping[0]->address }}</div>
                </td>
                <td class="w-half">
                    <div><h4>From:</h4></div>
                    <div>Laravel Daily</div>
                    <div>London</div>
                </td>
            </tr>
        </table>
    </div>
 
    <div class="margin-top">
        <table class="products">
            <tr>
                <th>Qty</th>
                <th>ProductId</th>
                <th>Price</th>
            </tr>
                @foreach($orderDetail as $item)
                <tr class="items">  
                    <td>
                        {{ $item['quantity'] }}
                    </td>
                    <td>
                        {{ $item['product_id'] }}
                    </td>
                    <td>
                        {{ ($item['unit_price'] /100) }}

                    </td>
                </tr>
                @endforeach
        </table>
    </div>
 
    <div class="total">
        Total: {{($order->total_amount/100)}}
    </div>
 
    <div class="footer margin-top">
        <div>Thank you</div>
        <div>&copy; {{ config('app.name') }}    </div>
    </div>
</body>
</html>