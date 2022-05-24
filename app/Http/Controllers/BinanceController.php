<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Support\Facades\Http;

class BinanceController extends Controller
{
    /**
     * Atributo do preço atual vindo da api
     * @type double
     */
    private $price;

    /**
     * Atributo do ultimo preço cadastrado no banco
     * @type object
     */
    private $lastPrice;

    /**
     * Atributo do percentual expecífico para calculo de média
     * @type string
     */
    private $percentFixed;

    /**
     * Atributo do resultado vindo da api
     * @type array
     */
    private $resultApi;

    /**
     * Atributo do resultado do calculo para imprimir no console
     */
    private $result;
    
    /**
     * Construtor da classe BinanceController
     */
    public function __construct() {
        /**
         * Setando valor fixo do percentual.
         */
        $this->percentFixed = '0,5';
    }
    
    /**
    *  Metodo responsavel por coletar preços solicitados e grava-los no banco de dados
    */
    public function savePrice(){
        /**
         * Faz a requisição na api e seta o seu resultado no atributo $resultApi
        */
        if(empty($this->resultApi)){
            $this->getDataApi();
        }
        
        /**
         * Chama a model para fazer a inserção dos novos valores.
         */
        $this->lastPrice = new Price();
        $this->lastPrice->price = $this->resultApi['price'];
        $this->lastPrice->created_at = date('Y-m-d H:i:s');
        $this->lastPrice->updated_at = date('Y-m-d H:i:s');
        $this->lastPrice->save();
        
        /**
         * Condição para que quando não houver sucesso ao cadastrar, retorne uma mensagem de erro ao usuário
         */
        if(!$this->lastPrice) return 'Houve um erro interno ao cadastrar novo preço, tente novamente mais tarde!';

        /**
         * Caso contrário, retorne uma mensagem para o usuário que tudo correu bem.
         */
        return 'Novo preço cadastrado com sucesso!';
    }
    
    /**
     * Metodo responsavel por fazer a requisição da api para retornar os valores de preços atuais
     */
    public function getDataApi(){
        /**
         * Faz a requisição na api e seta o seu resultado no atributo $resultApi
        */
        $this->resultApi = $this->connector('/fapi/v1/ticker/price');
    }

    /**
    * Metodo responsavel por fazer a solicitação na API coletando
    * o preço atual da crypto.
    */
    public function getPrice(){
        $this->getDataApi();
        /**
         * Coleta informações do preço gravado no banco
         */
        $this->lastPrice = (new Price())->orderBy('created_at','desc')->first();
        
        //Caso não exista nada cadastrado ainda, ele faz o primeiro insert no banco
        if(!$this->lastPrice){
            $this->savePrice();
        }
        return $this;
    }
    
    /**
    * Metodo responsavel por fazer a checkagem do preço atual com o ultimo
    * preço gravado no banco.
    */
    public function checkPrice(){
        /**
         * Faz a coleta de dados da api juntamente com os dados do banco
         */
        $this->getPrice();
        
        /**
         * Faz o calculo para retorno no console
         */
        $this->result = $this->calcPrice();
        
        /**
         * Condição de validação do preço pela porcentagem e exibir no console
         */
        if($this->result < $this->percentFixed){
            return "Último preço está menor que 0,5% ( {$this->result}% ) (Preço Anterior: {$this->lastPrice['price']}) (Preço Atual: {$this->resultApi['price']})";
        } else {
            return "Aguardar momento certo para compra! ( {$this->result}% ) (Preço Anterior: {$this->lastPrice['price']}) (Preço Atual: {$this->resultApi['price']})";
        }
    }
    
    /**
    * Metodo responsavel por fazer o calculo dos 0,5% e retornta-lo para a checkagem
    */
    public function calcPrice(){
        
        $valor = $this->lastPrice['price'];
        $this->price = $this->resultApi['price'];
        
        $primeiroCalculo = $valor - $this->price;
        $segundoCalculo = $primeiroCalculo / $valor;
        
        return $this->result = number_format($segundoCalculo * 100, '2', ',', '.');
    }
    
    /**
    * Metodo responsavel por fazer a conexão com a api
    */
    public function connector($endpoint){
        $response = Http::withHeaders([
            'X-MBX-APIKEY' => env('API_KEY')
            ])->get(env('API_ENDPOINT').$endpoint.'?symbol=BTCUSDT');
            
        return $response->json();
    }
}
