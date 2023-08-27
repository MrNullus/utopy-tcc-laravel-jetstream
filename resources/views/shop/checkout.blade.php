<x-app-layout>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    @php
    //use Illuminate\Support\Facades\Auth;
    MercadoPago\SDK::setAccessToken('TEST-3139979623211968-060716-10ec8969b083a7323e0f7c0b5088ad9e-396778896');

    // Cria um objeto de preferência
    $preference = new MercadoPago\Preference();

    // Cria um item na preferência
    $item = new MercadoPago\Item();
    $item->title = 'Meu produto';
    $item->quantity = 1;
    $item->unit_price = 75.56;
    $preference->items = array($item);

    // Cria um pagador
    $payer = new MercadoPago\Payer();
    $payer->name = \Illuminate\Support\Facades\Auth::user()->firstname;
    $payer->firstname = \Illuminate\Support\Facades\Auth::user()->firstname;
    $payer->lastname = \Illuminate\Support\Facades\Auth::user()->lastname;
    $payer->email = \Illuminate\Support\Facades\Auth::user()->email;
    $preference->payer = $payer;


    $preference->save();
    
    var_dump($preference);
    @endphp

    <div id="wallet_container"></div>

              
    <script type="text/javascript">
        window.location = '{{ $preference->init_point }}';
    </script>
</x-app-layout>
