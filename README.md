## Prova técnica IPorto

Esta aplicação foi desenovlvida no intuito de verificar o preço medio de uma determinada moeda aqui como BTCUSDT.

Para instalar, rode os seguintes comandos:

Rode o composer:
``
composer install
``

Rode e instale as migrations (tabelas)
``
php artisan migrate
``

Pronto, agora o sistema está pronto para ser executado

Rote os comandos
``
 php artisan c:saveBidPriceOnDataBase
``
- Esse comando salvará os dados de preço na base de dados.

``
php artisan c:checkAvgBigPrice
``
- Esse comando deverá verificar o preço médio e informar se o preço (último) está menor do que 0.5% do que o preço médio.


## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
