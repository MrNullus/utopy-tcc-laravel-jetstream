<?php

namespace App\Http\Controllers\User;
// TODO: FAZER COM QUE APENAS UM JOGO ENTRE PARA O CARRINHO
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Shopcart;
use App\Service\VendaService;
use Illuminate\Support\Facades\Auth;

class ShopcartController extends Controller
{
    public function countShopcart() {
        return Shopcart::where('user_id', Auth::id())->count();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Shopcart::join('games', 'games.id', '=', 'shopcarts.game_id')
            ->select('shopcarts.*', 'shopcarts.id as shopcart_id', 'games.*', 'games.id as game_game_id')
            ->get();

        return view('client.shopcart',[
            'shopcart' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = Shopcart::
            where('id', $request->id)
                ->where('user_id', Auth::id())
                ->first();

        if ($data) {
            $data->quantity += $request->input('quantity');
        } else {
            $data = new Shopcart();

            $data->game_id  = $request->input('game_id');
            $data->user_id  = Auth::id();
            $data->quantity = $request->input('quantity');
        }

        $data->save();
        return redirect()
            ->back()
            ->with('success','Produto adicionado ao Carrinho');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shopcart $shopcart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Shopcart $shopcart,$id)
    {
        Shopcart::where([ 'id' => $id ])->update([
            'quantity' => $request->input('quantity')
        ]);

        return redirect()
            ->route('shopcart.index')
            ->with('success', 'Carrinho atualizado com sucesso!');
    }

    public function checkout(Request $request) {
        $produtos = Shopcart::
            join('games', 'games.id', '=', 'shopcarts.game_id')
                ->select('shopcarts.*', 'games.*')
                ->get();

        $vendaService = new VendaService();
        $result = $vendaService->finalizarVenda($produtos, Auth::user());

        if ($result['status'] == 'ok') {
            $credCard = new PagSeguro\Domains\Requests\DirectPayment\CreditCard();
            $credCard->setReference("PED_" . $result['idpedido']);
            $credCard->setCurrency("BRL");
            
            foreach($produtos as $produto) {
                $credCard->addItems()->with(
                    $produto->id,
                    $produto->name,
                    1, 
                    number_format($produto->price, 2, '.', "")
                );
            }

            $user = Auth::user();

            $credCard->setSender()->setName($user->firstname . " " . $user->lastname);    
            $credCard->setSender()->setEmail($user->firstname . "@sandbox.pagseguro.com.br");
            $credCard->setSender()->setHash($request->input('hashSeller'));
            $credCard->setSender()->setPhone()->withParameters(21, 87645532);
            $credCard->setSender()->setDocument()->withParameters('cpf', $user->cpf);  

            $credCard->setShipping->setAddress()->withParameters(
                'Av 3',
                '1234',
                'Jardim',
                '22248884',
                'Acre',
                'AC',
                'BRA',
                'Dino'  
            );
            $credCard->setBilling->setAddress()->withParameters(
                'Av 3',
                '1234',
                'Jardim',
                '22248884',
                'Acre',
                'AC',
                'BRA',
                'Dino'  
            );
            
            $credCard->setToken($request->input('cardToken')); 
            $nparcela = $request->input('nparcela');  
            $totalParcela = $request->input('totalApagar');  
            $totalParcela = $request->input('totalParcela');  

            // Dados titular cartão
            $credCard->setHolder->setName($user->firstname . " " . $user->lastname);   
            $credCard->setHolder->setDocument()->withParameters("cpf", $user->cpf);            $credCard->setHolder->setBirthdate("01/01/1980");
            $credCard->setHolder->setPhone()->withParameters(21, 87645532); 

            $credCard->setMode('DEFAULT');
            $result = $credCard->register($this->getCredential());

            echo "Compra finalizada com sucesso!";  
         } else {
            echo "Compra não finalizada!";  
         } 
         
         if ($result['status'] === 'success') { 
            Shopcart::where('user_id', Auth::id())->delete();
        }

        $request->session()->flash($result['status'], $result['message']);
        return redirect()->route('shop.historic'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Shopcart::destroy($id);

        return redirect()
            ->route('shopcart.index')
            ->with('success', 'Produto retirado do carrinho! 😥');
    }
}
