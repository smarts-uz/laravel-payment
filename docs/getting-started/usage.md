# Usage
Available payment drivers:
* click
* payme
* paynet

## *.blade.php

It opens payment system web page for pay

```html
<form action="{{route('payment.client.checkout')}}" method="GET">
    <input type="text" name="key" value="{{ $model->id }}">
    <input type="number" name="amount" value="1000">
    <input type="text" name="payment_system" value="{{ $driver_name }}" ">
    <button type="submit">Оплата</button>
</form>
```


For handle payment system callbacks. You define it payment system dashboard.

```text
url: yourDomain/payment/{{ $driver_name }} 
method: Get|Post // suggestion is Post
```
